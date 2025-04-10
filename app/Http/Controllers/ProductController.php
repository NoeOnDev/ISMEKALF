<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['brand', 'supplier', 'creator']);

        // Filtros aplicados desde la Historia de Usuario 3.3
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('model', 'like', "%{$search}%")
                  ->orWhere('serial_number', 'like', "%{$search}%")
                  ->orWhere('cb_key', 'like', "%{$search}%");
            });
        }

        if ($request->filled('brand')) {
            $query->where('brand_id', $request->brand);
        }

        if ($request->filled('supplier')) {
            $query->where('supplier_id', $request->supplier);
        }

        // Solo los administradores pueden ver todos los productos
        // Los usuarios de almacén pueden ver todos los productos, pero solo editar los suyos
        // Esta línea se elimina o comenta
        // if (Auth::user()->hasRole('almacen') && !Auth::user()->hasRole('administrador')) {
        //     $query->where('created_by', Auth::id());
        // }

        $products = $query->latest()->paginate(10)->withQueryString();
        $brands = Brand::where('active', true)->orderBy('name')->get();
        $suppliers = Supplier::where('active', true)->orderBy('name')->get();

        return view('products.index', compact('products', 'brands', 'suppliers'));
    }

    public function create()
    {
        $brands = Brand::where('active', true)->orderBy('name')->get();
        $suppliers = Supplier::where('active', true)->orderBy('name')->get();
        return view('products.create', compact('brands', 'suppliers'));
    }

    public function store(Request $request)
    {
        $messages = [
            'name.required' => 'El nombre del producto es obligatorio.',
            'quantity.required' => 'La cantidad es obligatoria.',
            'quantity.integer' => 'La cantidad debe ser un número entero.',
            'quantity.min' => 'La cantidad no puede ser negativa.',
            'brand_id.exists' => 'La marca seleccionada no existe en la base de datos.',
            'supplier_id.exists' => 'El proveedor seleccionado no existe en la base de datos.',
            'image.image' => 'El archivo debe ser una imagen.',
            'image.max' => 'El tamaño máximo de la imagen es 2MB.',
            'expiration_date.date' => 'El formato de fecha de caducidad es inválido.',
            'freight_cost.numeric' => 'El costo de flete debe ser un valor numérico.',
        ];

        $validated = $request->validate([
            // Sección 1: Datos Generales y de Identificación
            'name' => 'required|string|max:255',
            'model' => 'nullable|string|max:100',
            'cb_key' => 'nullable|string|max:100',
            'serial_number' => 'nullable|string|max:100',
            'batch' => 'nullable|string|max:100',
            'group' => 'nullable|string|max:100',

            // Sección 2: Datos de Clasificación y Origen
            'brand_id' => 'nullable|exists:brands,id',
            'specialty_area' => 'nullable|string|max:100',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'brand_reference' => 'nullable|string|max:100',

            // Sección 3: Datos Operativos y Adicionales
            'location' => 'nullable|string|max:100',
            'manufacturer_unit' => 'nullable|string|max:50',
            'freight_company' => 'nullable|string|max:100',
            'freight_cost' => 'nullable|numeric|min:0',
            'expiration_date' => 'nullable|date',
            'quantity' => 'required|integer|min:0',
            'description' => 'nullable|string|max:1000',
            'image' => 'nullable|image|max:2048', // Máximo 2MB
        ], $messages);

        // Manejar la carga de la imagen
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $validated['image_path'] = $path;
        }

        // Registrar el usuario que creó el producto
        $validated['created_by'] = Auth::id();

        Product::create($validated);

        return redirect()->route('products.index')
            ->with('success', 'Producto creado correctamente.');
    }

    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $brands = Brand::where('active', true)->orderBy('name')->get();
        $suppliers = Supplier::where('active', true)->orderBy('name')->get();
        return view('products.edit', compact('product', 'brands', 'suppliers'));
    }

    public function update(Request $request, Product $product)
    {
        // Si el usuario no es administrador y no es el creador, no puede editar
        if (!Auth::user()->hasRole('administrador') && $product->created_by !== Auth::id()) {
            return redirect()->route('products.index')
                ->with('error', 'No tienes permisos para editar este producto.');
        }

        $messages = [
            'name.required' => 'El nombre del producto es obligatorio.',
            'quantity.required' => 'La cantidad es obligatoria.',
            'quantity.integer' => 'La cantidad debe ser un número entero.',
            'quantity.min' => 'La cantidad no puede ser negativa.',
            'brand_id.exists' => 'La marca seleccionada no existe en la base de datos.',
            'supplier_id.exists' => 'El proveedor seleccionado no existe en la base de datos.',
            'image.image' => 'El archivo debe ser una imagen.',
            'image.max' => 'El tamaño máximo de la imagen es 2MB.',
        ];

        $validated = $request->validate([
            // Sección 1: Datos Generales y de Identificación
            'name' => 'required|string|max:255',
            'model' => 'nullable|string|max:100',
            'cb_key' => 'nullable|string|max:100',
            'serial_number' => 'nullable|string|max:100',
            'batch' => 'nullable|string|max:100',
            'group' => 'nullable|string|max:100',

            // Sección 2: Datos de Clasificación y Origen
            'brand_id' => 'nullable|exists:brands,id',
            'specialty_area' => 'nullable|string|max:100',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'brand_reference' => 'nullable|string|max:100',

            // Sección 3: Datos Operativos y Adicionales
            'location' => 'nullable|string|max:100',
            'manufacturer_unit' => 'nullable|string|max:50',
            'freight_company' => 'nullable|string|max:100',
            'freight_cost' => 'nullable|numeric|min:0',
            'expiration_date' => 'nullable|date',
            'quantity' => 'required|integer|min:0',
            'description' => 'nullable|string|max:1000',
            'image' => 'nullable|image|max:2048',
        ], $messages);

        // Manejar la imagen
        if ($request->hasFile('image')) {
            // Eliminar imagen anterior si existe
            if ($product->image_path) {
                Storage::disk('public')->delete($product->image_path);
            }

            $path = $request->file('image')->store('products', 'public');
            $validated['image_path'] = $path;
        }

        // Actualizar el producto
        $product->update($validated);

        return redirect()->route('products.index')
            ->with('success', 'Producto actualizado correctamente.');
    }

    public function destroy(Product $product)
    {
        // Si el usuario no es administrador, no puede eliminar
        if (!Auth::user()->hasRole('administrador')) {
            return redirect()->route('products.index')
                ->with('error', 'No tienes permisos para eliminar productos.');
        }

        // Eliminar imagen si existe
        if ($product->image_path) {
            Storage::disk('public')->delete($product->image_path);
        }

        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Producto eliminado correctamente.');
    }
}
