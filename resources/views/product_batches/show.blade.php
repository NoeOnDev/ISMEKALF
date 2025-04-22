<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-4 md:mb-0">
                {{ __('Detalle de Lote') }}
            </h2>
            <div class="flex flex-col sm:flex-row sm:space-x-2 space-y-2 sm:space-y-0 w-full md:w-auto md:ml-auto">
                <a href="{{ route('inventory.index') }}"
                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded text-center">
                    Volver al listado
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Columna 1: Datos del producto -->
                        <div>
                            <div class="bg-gray-50 p-4 rounded-lg mb-4">
                                <h3 class="text-lg font-semibold mb-2">Información del Producto</h3>
                                <p class="text-sm"><span class="font-medium">Nombre:</span> {{ $batch->product->name }}
                                </p>
                                <p class="text-sm"><span class="font-medium">Modelo:</span>
                                    {{ $batch->product->model ?? 'N/A' }}</p>
                                <p class="text-sm"><span class="font-medium">Marca:</span>
                                    {{ $batch->product->brand->name ?? 'N/A' }}</p>
                                <p class="text-sm"><span class="font-medium">Proveedor:</span>
                                    {{ $batch->product->supplier->name ?? 'N/A' }}</p>
                            </div>
                        </div>

                        <!-- Columna 2: Datos del lote -->
                        <div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h3 class="text-lg font-semibold mb-2">Información del Lote</h3>
                                <p class="text-sm"><span class="font-medium">Número de Lote:</span>
                                    {{ $batch->batch_number ?? 'N/A' }}</p>
                                <p class="text-sm"><span class="font-medium">Cantidad:</span> {{ $batch->quantity }}</p>
                                <p class="text-sm"><span class="font-medium">Ubicación:</span>
                                    {{ $batch->location ?? 'N/A' }}</p>
                                <p class="text-sm"><span class="font-medium">Fecha de Caducidad:</span>
                                    @if ($batch->expiration_date)
                                        <span
                                            class="{{ $batch->expiration_date->isPast() ? 'text-red-600' : ($batch->expiration_date->diffInDays(now()) < 30 ? 'text-yellow-600' : 'text-gray-900') }}">
                                            {{ $batch->expiration_date->format('d/m/Y') }}
                                        </span>
                                    @else
                                        <span>No aplica</span>
                                    @endif
                                </p>
                                <p class="text-sm"><span class="font-medium">Creado por:</span>
                                    {{ $batch->creator->name ?? 'N/A' }}</p>
                                <p class="text-sm"><span class="font-medium">Fecha de Creación:</span>
                                    {{ $batch->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                    </div>

                    @if ($batch->notes)
                        <div class="mt-6">
                            <h3 class="text-lg font-semibold mb-2">Notas</h3>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-sm">{{ $batch->notes }}</p>
                            </div>
                        </div>
                    @endif

                    @if (auth()->user()->hasRole('administrador'))
                        <div class="mt-6 flex justify-end">
                            <form action="{{ route('products.batches.destroy', $batch) }}" method="POST"
                                onsubmit="return confirm('¿Estás seguro de eliminar este lote?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                    Eliminar Lote
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
