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

    public function allBatches(Request $request)
    {
        $query = ProductBatch::with(['product', 'product.brand']);

        // Filtros
        if ($request->has('status')) {
            switch ($request->status) {
                case 'expired':
                    $query->whereNotNull('expiration_date')
                        ->where('expiration_date', '<', now())
                        ->where('quantity', '>', 0);
                    break;
                case 'expiring_soon':
                    $query->whereNotNull('expiration_date')
                        ->where('expiration_date', '>=', now())
                        ->where('expiration_date', '<=', now()->addDays(30))
                        ->where('quantity', '>', 0);
                    break;
                case 'valid':
                    $query->where(function($q) {
                        $q->whereNull('expiration_date')
                          ->orWhere('expiration_date', '>', now()->addDays(30));
                    })
                    ->where('quantity', '>', 0);
                    break;
                case 'out_of_stock':
                    $query->where('quantity', '=', 0);
                    break;
            }
        }

        if ($request->filled('product')) {  // usando filled en lugar de has
            $query->where('product_id', $request->product);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('product', function($subq) use ($search) {
                    $subq->where('name', 'like', "%{$search}%")
                        ->orWhere('model', 'like', "%{$search}%")
                        ->orWhere('batch', 'like', "%{$search}%");
                })
                ->orWhere('batch_number', 'like', "%{$search}%");
            });
        }

        // Ordenamiento
        $sortField = $request->sort ?? 'expiration_date';
        $sortDirection = $request->direction ?? 'asc';
        $query->orderBy($sortField, $sortDirection);

        $batches = $query->paginate(15)->withQueryString();
        $products = Product::orderBy('name')->get(); // Para el filtro

        return view('product_batches.all', compact('batches', 'products'));
    }

    public function exportAllBatches(Request $request)
    {
        // Solo permitir que los administradores exporten
        if (!Auth::user()->hasRole('administrador')) {
            return redirect()->route('inventory.index')
                ->with('error', 'No tienes permisos para exportar el inventario.');
        }

        $request->validate([
            'fields' => 'required|array|min:1',
            'fields.*' => 'string',
        ]);

        $fields = $request->fields;

        $query = ProductBatch::with(['product', 'product.brand', 'creator']);

        // Aplicar los mismos filtros que en allBatches
        if ($request->has('status')) {
            switch ($request->status) {
                case 'expired':
                    $query->whereNotNull('expiration_date')
                        ->where('expiration_date', '<', now())
                        ->where('quantity', '>', 0);
                    break;
                case 'expiring_soon':
                    $query->whereNotNull('expiration_date')
                        ->where('expiration_date', '>=', now())
                        ->where('expiration_date', '<=', now()->addDays(30))
                        ->where('quantity', '>', 0);
                    break;
                case 'valid':
                    $query->where(function($q) {
                        $q->whereNull('expiration_date')
                          ->orWhere('expiration_date', '>', now()->addDays(30));
                    })
                    ->where('quantity', '>', 0);
                    break;
                case 'out_of_stock':
                    $query->where('quantity', '=', 0);
                    break;
            }
        }

        if ($request->filled('product')) {  // usando filled en lugar de has
            $query->where('product_id', $request->product);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('product', function($subq) use ($search) {
                    $subq->where('name', 'like', "%{$search}%")
                        ->orWhere('model', 'like', "%{$search}%")
                        ->orWhere('batch', 'like', "%{$search}%");
                })
                ->orWhere('batch_number', 'like', "%{$search}%");
            });
        }

        $batches = $query->get();

        // Generar el nombre del archivo
        $filename = 'inventario_lotes_' . date('Y-m-d_His') . '.csv';

        // Crear una respuesta para el archivo CSV
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        return response()->stream(function () use ($batches, $fields) {
            // Abrir salida para escritura
            $output = fopen('php://output', 'w');

            // Agregar BOM para la correcta interpretación de caracteres UTF-8
            fputs($output, "\xEF\xBB\xBF");

            // Definir encabezados del CSV basados en los campos seleccionados
            $csvHeaders = [];

            // Preparar los encabezados según los campos seleccionados
            foreach ($fields as $field) {
                switch ($field) {
                    case 'product_name':
                        $csvHeaders[] = 'Producto';
                        break;
                    case 'brand':
                        $csvHeaders[] = 'Marca';
                        break;
                    case 'batch_number':
                        $csvHeaders[] = 'Lote';
                        break;
                    case 'quantity':
                        $csvHeaders[] = 'Cantidad';
                        break;
                    case 'location':
                        $csvHeaders[] = 'Ubicación';
                        break;
                    case 'expiration_date':
                        $csvHeaders[] = 'Fecha de Caducidad';
                        break;
                    case 'status':
                        $csvHeaders[] = 'Estado';
                        break;
                    case 'creator':
                        $csvHeaders[] = 'Creado Por';
                        break;
                    case 'created_at':
                        $csvHeaders[] = 'Fecha de Creación';
                        break;
                    case 'notes':
                        $csvHeaders[] = 'Notas';
                        break;
                }
            }

            fputcsv($output, $csvHeaders);

            // Escribir datos
            foreach ($batches as $batch) {
                $expirationStatus = 'Sin caducidad';

                if ($batch->expiration_date) {
                    if ($batch->expiration_date->isPast()) {
                        $expirationStatus = 'Caducado';
                    } elseif ($batch->expiration_date->diffInDays(now()) <= 30) {
                        $expirationStatus = 'Por caducar';
                    } else {
                        $expirationStatus = 'Vigente';
                    }
                }

                $row = [];

                // Agregar datos según los campos seleccionados
                foreach ($fields as $field) {
                    switch ($field) {
                        case 'product_name':
                            $row[] = $batch->product->name;
                            break;
                        case 'brand':
                            $row[] = $batch->product->brand->name ?? 'N/A';
                            break;
                        case 'batch_number':
                            $row[] = $batch->batch_number ?? 'N/A';
                            break;
                        case 'quantity':
                            $row[] = $batch->quantity;
                            break;
                        case 'location':
                            $row[] = $batch->location ?? 'N/A';
                            break;
                        case 'expiration_date':
                            $row[] = $batch->expiration_date ? $batch->expiration_date->format('d/m/Y') : 'N/A';
                            break;
                        case 'status':
                            $row[] = $expirationStatus;
                            break;
                        case 'creator':
                            $row[] = $batch->creator->name ?? 'Desconocido';
                            break;
                        case 'created_at':
                            $row[] = $batch->created_at->format('d/m/Y H:i');
                            break;
                        case 'notes':
                            $row[] = $batch->notes ?? 'N/A';
                            break;
                    }
                }

                fputcsv($output, $row);
            }

            fclose($output);
        }, 200, $headers);
    }

    public function show(ProductBatch $batch)
    {
        return view('product_batches.show', compact('batch'));
    }
}
