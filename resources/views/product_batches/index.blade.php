<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-4 md:mb-0">
                {{ __('Inventario de') }}: {{ $product->name }}
            </h2>
            <div class="flex flex-col sm:flex-row sm:space-x-2 space-y-2 sm:space-y-0 w-full md:w-auto md:ml-auto">
                <a href="{{ route('products.batches.create', $product) }}"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-center">
                    Agregar Inventario
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-6">
                        <h3 class="text-lg font-medium">Información general del producto</h3>
                        <p class="text-sm mt-2"><span class="font-medium">Cantidad total:</span>
                            {{ $product->getTotalQuantityAttribute() }}</p>
                        <p class="text-sm"><span class="font-medium">Categoría:</span> {{ $product->group ?? 'N/A' }}
                        </p>
                        <p class="text-sm"><span class="font-medium">Marca:</span> {{ $product->brand->name ?? 'N/A' }}
                        </p>
                    </div>

                    <h3 class="text-lg font-medium mb-4">Lotes disponibles</h3>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Lote</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Ubicación</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Cantidad</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Caducidad</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Fecha agregado</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($batches as $batch)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $batch->batch_number ?? 'N/A' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $batch->location ?? 'N/A' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $batch->quantity }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if ($batch->expiration_date)
                                                <div
                                                    class="text-sm {{ $batch->expiration_date->isPast() ? 'text-red-600' : ($batch->expiration_date->diffInDays(now()) < 30 ? 'text-yellow-600' : 'text-gray-900') }}">
                                                    {{ $batch->expiration_date->format('d/m/Y') }}
                                                </div>
                                            @else
                                                <div class="text-sm text-gray-500">No aplica</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-500">
                                                {{ $batch->created_at->format('d/m/Y') }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            @if (auth()->user()->hasRole('administrador'))
                                                <form class="inline"
                                                    action="{{ route('products.batches.destroy', $batch) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('¿Estás seguro de eliminar este lote?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="text-red-600 hover:text-red-900">Eliminar</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6"
                                            class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                                            No hay lotes registrados para este producto.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $batches->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
