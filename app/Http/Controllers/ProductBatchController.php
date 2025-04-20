<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductBatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductBatchController extends Controller
{
    // Mostrar el formulario para agregar nuevo lote
    public function create(Product $product)
    {
        return view('product_batches.create', compact('product'));
    }

    // Almacenar nuevo lote
    public function store(Request $request, Product $product)
    {
        $validated = $request->validate([
            'batch_number' => 'nullable|string|max:100',
            'expiration_date' => 'nullable|date',
            'quantity' => 'required|integer|min:1',
            'location' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:1000',
        ]);

        $validated['product_id'] = $product->id;
        $validated['created_by'] = Auth::id();

        ProductBatch::create($validated);

        // Actualizar la cantidad total del producto
        $product->quantity = $product->getTotalQuantityAttribute();
        $product->save();

        return redirect()->route('products.show', $product)
            ->with('success', 'Se ha agregado inventario correctamente.');
    }

    // Gestionar lotes (listar, editar, etc)
    public function index(Product $product)
    {
        $batches = $product->batches()->latest()->paginate(10);
        return view('product_batches.index', compact('product', 'batches'));
    }

    // Eliminar lote (por ejemplo, cuando está caducado)
    public function destroy(ProductBatch $batch)
    {
        if (!Auth::user()->hasRole('administrador')) {
            return redirect()->back()
                ->with('error', 'No tienes permisos para eliminar lotes.');
        }

        $product = $batch->product;
        $batch->delete();

        // Actualizar la cantidad total del producto
        $product->quantity = $product->getTotalQuantityAttribute();
        $product->save();

        return redirect()->route('products.batches.index', $product)
            ->with('success', 'Lote eliminado correctamente.');
    }
}
