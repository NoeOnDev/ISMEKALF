<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-4 md:mb-0">
                {{ __('Inventario General') }}
            </h2>
            <div class="flex flex-col sm:flex-row sm:space-x-2 space-y-2 sm:space-y-0 w-full md:w-auto md:ml-auto">
                @if (auth()->user()->hasRole('administrador'))
                    <button onclick="openExportModal()"
                        class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-center">
                        <i class="fas fa-file-export mr-1"></i> Exportar CSV
                    </button>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4" role="alert">
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            <!-- Filtros de búsqueda -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="GET" action="{{ route('inventory.index') }}">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label for="search" class="block text-sm font-medium text-gray-700">Buscar</label>
                                <input type="text" name="search" id="search" value="{{ request('search') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    placeholder="Nombre, lote...">
                            </div>

                            <div>
                                <label for="product" class="block text-sm font-medium text-gray-700">Producto</label>
                                <select name="product" id="product"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Todos los productos</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}"
                                            {{ request('product') == $product->id ? 'selected' : '' }}>
                                            {{ $product->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">Estado</label>
                                <select name="status" id="status"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Todos los estados</option>
                                    <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>
                                        Caducados</option>
                                    <option value="expiring_soon"
                                        {{ request('status') == 'expiring_soon' ? 'selected' : '' }}>Por caducar (30
                                        días)</option>
                                    <option value="valid" {{ request('status') == 'valid' ? 'selected' : '' }}>
                                        Vigentes</option>
                                    <option value="out_of_stock"
                                        {{ request('status') == 'out_of_stock' ? 'selected' : '' }}>Sin stock</option>
                                </select>
                            </div>

                            <div class="flex items-end">
                                <button type="submit"
                                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                                    Filtrar
                                </button>
                                <a href="{{ route('inventory.index') }}"
                                    class="ml-2 bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                                    Limpiar
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Producto
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Lote
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Cantidad
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Caducidad
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Fecha Creación
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($batches as $batch)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                <a href="{{ route('products.show', $batch->product) }}"
                                                    class="hover:text-indigo-600">
                                                    {{ $batch->product->name }}
                                                </a>
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ $batch->product->brand->name ?? 'N/A' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $batch->batch_number ?? 'N/A' }}
                                            </div>
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
                                            <div class="text-sm text-gray-900">
                                                {{ $batch->created_at->format('d/m/Y') }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('products.batches.show', $batch) }}"
                                                class="text-blue-600 hover:text-blue-900 mr-3">
                                                Ver detalles
                                            </a>
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
                                            No hay lotes registrados con los criterios seleccionados.
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

    <!-- Modal para exportar inventario -->
    <div id="exportModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 overflow-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Exportar inventario a CSV</h3>
                        <button type="button" onclick="closeExportModal()" class="text-gray-500 hover:text-gray-800">
                            <span class="text-2xl">&times;</span>
                        </button>
                    </div>

                    <form action="{{ route('inventory.export') }}" method="POST">
                        @csrf
                        <!-- Aplicar filtros actuales -->
                        <input type="hidden" name="search" value="{{ request('search') }}">
                        <input type="hidden" name="product" value="{{ request('product') }}">
                        <input type="hidden" name="status" value="{{ request('status') }}">

                        <div class="mb-4">
                            <p class="text-sm text-gray-600 mb-2">Seleccione los campos que desea exportar:</p>
                            <div class="grid grid-cols-2 gap-2">
                                <!-- Datos del producto -->
                                <div class="col-span-2 mt-2 mb-1">
                                    <span class="font-medium text-gray-700">Datos del producto:</span>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" name="fields[]" value="product_name"
                                        id="field_product_name"
                                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                        checked>
                                    <label for="field_product_name" class="ml-2 text-sm text-gray-700">Nombre del
                                        producto</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" name="fields[]" value="brand" id="field_brand"
                                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                        checked>
                                    <label for="field_brand" class="ml-2 text-sm text-gray-700">Marca</label>
                                </div>

                                <!-- Datos del lote -->
                                <div class="col-span-2 mt-2 mb-1">
                                    <span class="font-medium text-gray-700">Datos del lote:</span>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" name="fields[]" value="batch_number"
                                        id="field_batch_number"
                                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                        checked>
                                    <label for="field_batch_number" class="ml-2 text-sm text-gray-700">Lote</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" name="fields[]" value="quantity" id="field_quantity"
                                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                        checked>
                                    <label for="field_quantity" class="ml-2 text-sm text-gray-700">Cantidad</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" name="fields[]" value="location" id="field_location"
                                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                        checked>
                                    <label for="field_location" class="ml-2 text-sm text-gray-700">Ubicación</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" name="fields[]" value="expiration_date"
                                        id="field_expiration_date"
                                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                        checked>
                                    <label for="field_expiration_date" class="ml-2 text-sm text-gray-700">Fecha de
                                        caducidad</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" name="fields[]" value="status" id="field_status"
                                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                        checked>
                                    <label for="field_status" class="ml-2 text-sm text-gray-700">Estado</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" name="fields[]" value="notes" id="field_notes"
                                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <label for="field_notes" class="ml-2 text-sm text-gray-700">Notas</label>
                                </div>

                                <!-- Datos adicionales -->
                                <div class="col-span-2 mt-2 mb-1">
                                    <span class="font-medium text-gray-700">Datos adicionales:</span>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" name="fields[]" value="creator" id="field_creator"
                                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <label for="field_creator" class="ml-2 text-sm text-gray-700">Creado por</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" name="fields[]" value="created_at" id="field_created_at"
                                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <label for="field_created_at" class="ml-2 text-sm text-gray-700">Fecha de
                                        creación</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" name="fields[]" value="specialty_area" id="field_specialty_area"
                                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                        checked>
                                    <label for="field_specialty_area" class="ml-2 text-sm text-gray-700">Área/Especialidad</label>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-between items-center mt-6">
                            <button type="button" onclick="toggleAllFields()"
                                class="text-sm text-indigo-600 hover:text-indigo-800">
                                Seleccionar/Deseleccionar todos
                            </button>
                            <div>
                                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">
                                    Exportar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openExportModal() {
            document.getElementById('exportModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeExportModal() {
            document.getElementById('exportModal').classList.add('hidden');
            document.body.style.overflow = '';
        }

        let allFieldsSelected = true;

        function toggleAllFields() {
            const checkboxes = document.querySelectorAll('input[name="fields[]"]');
            allFieldsSelected = !allFieldsSelected;

            checkboxes.forEach(checkbox => {
                checkbox.checked = allFieldsSelected;
            });
        }
    </script>
</x-app-layout>
