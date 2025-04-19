<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detalles del Producto') }}
            </h2>
            <a href="{{ route('products.index') }}"
                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Volver al listado
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Columna 1: Imagen y datos básicos -->
                        <div class="col-span-1">
                            @if ($product->image_path)
                                <div class="mb-4 cursor-pointer"
                                    onclick="openImageModal('{{ asset('storage/' . $product->image_path) }}')">
                                    <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}"
                                        class="w-full max-h-96 object-contain rounded-lg shadow-md">
                                    <div class="text-xs text-gray-500 mt-1 text-center">Clic para ampliar</div>
                                </div>
                            @else
                                <div class="mb-4 bg-gray-100 rounded-lg p-4 flex items-center justify-center h-48">
                                    <p class="text-gray-500">Sin imagen</p>
                                </div>
                            @endif

                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h3 class="text-lg font-semibold mb-2">Información Básica</h3>
                                <p class="text-sm"><span class="font-medium">Nombre:</span> {{ $product->name }}</p>
                                <p class="text-sm"><span class="font-medium">Modelo:</span>
                                    {{ $product->model ?? 'N/A' }}</p>
                                <p class="text-sm"><span class="font-medium">Cantidad:</span> {{ $product->quantity }}
                                </p>
                                <p class="text-sm"><span class="font-medium">Marca:</span>
                                    {{ $product->brand->name ?? 'N/A' }}</p>
                                <p class="text-sm"><span class="font-medium">Proveedor:</span>
                                    {{ $product->supplier->name ?? 'N/A' }}</p>
                            </div>
                        </div>

                        <!-- Columna 2: Datos de identificación y clasificación -->
                        <div class="col-span-1">
                            <div class="bg-gray-50 p-4 rounded-lg mb-4">
                                <h3 class="text-lg font-semibold mb-2">Datos de Identificación</h3>
                                <p class="text-sm"><span class="font-medium">Clave CB:</span>
                                    {{ $product->cb_key ?? 'N/A' }}</p>
                                <p class="text-sm"><span class="font-medium">Número de Serie:</span>
                                    {{ $product->serial_number ?? 'N/A' }}</p>
                                <p class="text-sm"><span class="font-medium">Lote:</span>
                                    {{ $product->batch ?? 'N/A' }}</p>
                                <p class="text-sm"><span class="font-medium">Grupo:</span>
                                    {{ $product->group ?? 'N/A' }}</p>
                            </div>

                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h3 class="text-lg font-semibold mb-2">Clasificación y Origen</h3>
                                <p class="text-sm"><span class="font-medium">Área/Especialidad:</span>
                                    {{ $product->specialty_area ?? 'N/A' }}</p>
                                <p class="text-sm"><span class="font-medium">Referencia Marca:</span>
                                    {{ $product->brand_reference ?? 'N/A' }}</p>
                            </div>
                        </div>

                        <!-- Columna 3: Datos operativos y adicionales -->
                        <div class="col-span-1">
                            <div class="bg-gray-50 p-4 rounded-lg mb-4">
                                <h3 class="text-lg font-semibold mb-2">Datos Operativos</h3>
                                <p class="text-sm"><span class="font-medium">Ubicación:</span>
                                    {{ $product->location ?? 'N/A' }}</p>
                                <p class="text-sm"><span class="font-medium">Unidad de Medida:</span>
                                    {{ $product->manufacturer_unit ?? 'N/A' }}</p>
                                <p class="text-sm"><span class="font-medium">Fletera:</span>
                                    {{ $product->freight_company ?? 'N/A' }}</p>
                                <p class="text-sm"><span class="font-medium">Costo de Flete:</span>
                                    ${{ number_format($product->freight_cost ?? 0, 2) }}</p>
                                <p class="text-sm"><span class="font-medium">Caducidad:</span>
                                    {{ $product->expiration_date ? $product->expiration_date->format('d/m/Y') : 'N/A' }}
                                </p>
                            </div>

                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h3 class="text-lg font-semibold mb-2">Información Adicional</h3>
                                <p class="text-sm"><span class="font-medium">Descripción:</span></p>
                                <p class="text-sm mt-1 text-gray-600">{{ $product->description ?? 'Sin descripción' }}
                                </p>
                                <p class="text-sm mt-2"><span class="font-medium">Creado por:</span>
                                    {{ $product->creator->name ?? 'Desconocido' }}</p>
                                <p class="text-sm"><span class="font-medium">Fecha de Creación:</span>
                                    {{ $product->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                    </div>

                    @if (auth()->user()->hasRole('administrador') || $product->created_by == auth()->id())
                        <div class="mt-6 flex justify-between">
                            <a href="{{ route('products.edit', $product) }}"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Editar Producto
                            </a>

                            @if (auth()->user()->hasRole('administrador'))
                                <form action="{{ route('products.destroy', $product) }}" method="POST"
                                    onsubmit="return confirm('¿Estás seguro de eliminar este producto?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                        Eliminar Producto
                                    </button>
                                </form>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de imagen mejorado con scroll -->
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-90 hidden z-50 overflow-auto">
        <div class="sticky top-0 w-full flex justify-end p-4">
            <button onclick="closeImageModal()"
                class="text-white bg-gray-800 rounded-full h-10 w-10 flex items-center justify-center hover:bg-gray-700">
                &times;
            </button>
        </div>
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="max-w-screen-xl max-h-full overflow-auto">
                <img id="modalImage" src="" alt="Imagen ampliada" class="max-w-full">
            </div>
        </div>
    </div>

    <script>
        function openImageModal(imageSrc) {
            document.getElementById('modalImage').src = imageSrc;
            document.getElementById('imageModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden'; // Prevenir scroll en el body
        }

        function closeImageModal() {
            document.getElementById('imageModal').classList.add('hidden');
            document.body.style.overflow = ''; // Restaurar scroll
        }
    </script>
</x-app-layout>
