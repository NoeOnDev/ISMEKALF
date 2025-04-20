<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('client')->latest()->paginate(10);
        return view('orders.index', compact('orders'));
    }

    public function createStep1()
    {
        // Limpiar la sesión al iniciar un nuevo proceso de remisión
        Session::forget(['cart', 'client_id']);

        $products = Product::where('quantity', '>', 0)->get();
        $cart = []; // Carrito vacío

        return view('orders.create-step1', compact('products', 'cart'));
    }

    public function storeStep1(Request $request)
    {
        // Eliminar la validación inicial que está causando el problema
        // $request->validate([
        //     'products' => 'required|array|min:1',
        //     'products.*.id' => 'required|exists:products,id',
        //     'products.*.quantity' => 'required|integer|min:1',
        // ]);

        $cart = [];
        $productData = $request->products ?? [];

        // Filtrar solo los productos que realmente fueron seleccionados
        $selectedProducts = [];
        foreach ($productData as $index => $item) {
            // Solo incluir productos con ID y cantidad > 0
            if (!empty($item['id']) && isset($item['quantity']) && $item['quantity'] > 0) {
                $selectedProducts[$index] = $item;
            }
        }

        // Validar solo los productos seleccionados
        if (count($selectedProducts) > 0) {
            foreach ($selectedProducts as $index => $item) {
                $product = Product::find($item['id']);

                if ($product && $product->quantity >= $item['quantity']) {
                    $cart[$item['id']] = [
                        'id' => $product->id,
                        'name' => $product->name,
                        'quantity' => $item['quantity'],
                        'available' => $product->quantity
                    ];
                }
            }
        }

        if (empty($cart)) {
            return redirect()->route('orders.create.step1')
                ->with('error', 'Debe seleccionar al menos un producto con cantidad válida.');
        }

        Session::put('cart', $cart);

        return redirect()->route('orders.create.step2');
    }

    public function createStep2()
    {
        $cart = Session::get('cart', []);

        if (empty($cart)) {
            return redirect()->route('orders.create.step1')
                ->with('error', 'Primero debe seleccionar productos.');
        }

        $clients = Client::orderBy('name')->get();
        $existingClient = null;
        $clientId = Session::get('client_id');

        if ($clientId) {
            $existingClient = Client::find($clientId);
        }

        return view('orders.create-step2', compact('cart', 'clients', 'existingClient'));
    }

    public function store(Request $request)
    {
        $cart = Session::get('cart', []);

        if (empty($cart)) {
            return redirect()->route('orders.create.step1')
                ->with('error', 'Primero debe seleccionar productos.');
        }

        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'notes' => 'nullable|string|max:1000',
        ]);

        DB::beginTransaction();

        try {
            // Crear la orden
            $order = Order::create([
                'remission_number' => Order::generateRemissionNumber(),
                'client_id' => $validated['client_id'],
                'notes' => $validated['notes'],
                'status' => 'completed',
                'created_by' => Auth::id(),
            ]);

            // Crear elementos de la orden y actualizar inventario
            foreach ($cart as $item) {
                $product = Product::findOrFail($item['id']);

                if ($product->quantity < $item['quantity']) {
                    throw new \Exception("Cantidad insuficiente para {$product->name}");
                }

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                ]);

                // Actualizar inventario considerando los lotes
                $quantityToReduce = $item['quantity'];

                // Obtener lotes con inventario disponible, ordenados por fecha de caducidad (primero los que caducan antes)
                $batches = $product->batches()
                    ->where('quantity', '>', 0)
                    ->orderBy('expiration_date', 'asc')
                    ->orderBy('created_at', 'asc')
                    ->get();

                foreach ($batches as $batch) {
                    if ($quantityToReduce <= 0) break;

                    $quantityFromBatch = min($batch->quantity, $quantityToReduce);
                    $batch->quantity -= $quantityFromBatch;
                    $batch->save();

                    $quantityToReduce -= $quantityFromBatch;
                }

                // Actualizar cantidad total del producto
                $product->quantity = $product->getTotalQuantityAttribute();
                $product->save();
            }

            DB::commit();
            Session::forget(['cart', 'client_id']);

            return redirect()->route('orders.show', $order->id)
                ->with('success', 'Remisión creada correctamente con número: ' . $order->remission_number);
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->route('orders.create.step2')
                ->with('error', 'Error al procesar la remisión: ' . $e->getMessage());
        }
    }

    public function show(Order $order)
    {
        $order->load(['items.product', 'client', 'creator']);
        return view('orders.show', compact('order'));
    }

    public function destroy(Order $order)
    {
        // Solo permitir cancelar si es administrador
        if (!Auth::user()->hasRole('administrador')) {
            return redirect()->route('orders.index')
                ->with('error', 'No tiene permisos para cancelar remisiones.');
        }

        DB::beginTransaction();

        try {
            // Restaurar el inventario
            foreach ($order->items as $item) {
                $product = $item->product;
                $product->quantity += $item->quantity;
                $product->save();
            }

            // Marcar como cancelada
            $order->status = 'cancelled';
            $order->save();

            DB::commit();

            return redirect()->route('orders.index')
                ->with('success', 'Remisión #' . $order->remission_number . ' ha sido cancelada y el inventario restaurado.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->route('orders.index')
                ->with('error', 'Error al cancelar la remisión: ' . $e->getMessage());
        }
    }
}
