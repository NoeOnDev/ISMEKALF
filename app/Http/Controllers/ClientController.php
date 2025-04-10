<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::latest()->paginate(10);
        return view('clients.index', compact('clients'));
    }

    public function create()
    {
        return view('clients.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:clients',
            'contact' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'version' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:500',
        ]);

        Client::create($validated);

        return redirect()->route('clients.index')
            ->with('success', 'Cliente creado correctamente.');
    }

    public function show(Client $client)
    {
        return view('clients.show', compact('client'));
    }

    public function edit(Client $client)
    {
        return view('clients.edit', compact('client'));
    }

    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => ['required', 'string', 'max:50', Rule::unique('clients')->ignore($client->id)],
            'contact' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'version' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:500',
        ]);

        $client->update($validated);

        return redirect()->route('clients.index')
            ->with('success', 'Cliente actualizado correctamente.');
    }

    public function destroy(Client $client)
    {
        // Verificar si el cliente tiene órdenes asociadas
        if ($client->orders()->count() > 0) {
            return redirect()->route('clients.index')
                ->with('error', 'No se puede eliminar el cliente porque tiene remisiones asociadas.');
        }

        $client->delete();

        return redirect()->route('clients.index')
            ->with('success', 'Cliente eliminado correctamente.');
    }
}
