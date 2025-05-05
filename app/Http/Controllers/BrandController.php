<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Brand::query();

        // Búsqueda por nombre
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }

        // Filtro por estado
        if ($request->filled('active')) {
            $query->where('active', $request->active == '1');
        }

        $brands = $query->latest()->paginate(10)->withQueryString();

        return view('brands.index', compact('brands'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('brands.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:brands',
            'description' => 'nullable|string|max:1000',
            'active' => 'boolean',
        ]);

        // Si no se envía el campo active, establecerlo como true por defecto
        if (!isset($validated['active'])) {
            $validated['active'] = true;
        }

        Brand::create($validated);

        return redirect()->route('brands.index')
            ->with('success', 'Marca creada correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Brand $brand)
    {
        return view('brands.show', compact('brand'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Brand $brand)
    {
        return view('brands.edit', compact('brand'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Brand $brand)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:brands,name,' . $brand->id,
            'description' => 'nullable|string|max:1000',
            'active' => 'boolean',
        ]);

        // Si no se envía el campo active, establecerlo como false
        if (!isset($validated['active'])) {
            $validated['active'] = false;
        }

        $brand->update($validated);

        return redirect()->route('brands.index')
            ->with('success', 'Marca actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Brand $brand)
    {
        // Verificar si la marca tiene productos asociados
        if ($brand->products()->count() > 0) {
            return redirect()->route('brands.index')
                ->with('error', 'No se puede eliminar la marca porque tiene productos asociados.');
        }

        $brand->delete();

        return redirect()->route('brands.index')
            ->with('success', 'Marca eliminada correctamente.');
    }
}
