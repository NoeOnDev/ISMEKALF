<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-4 md:mb-0">
                {{ __('Productos') }}
            </h2>
            <div class="flex flex-col sm:flex-row sm:space-x-2 space-y-2 sm:space-y-0 w-full md:w-auto md:ml-auto">
                @if (auth()->user()->hasRole('administrador'))
                    <button onclick="openExportModal()"
                        class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-center">
                        <i class="fas fa-file-export mr-1"></i> Exportar CSV
                    </button>
                @endif
                <a href="{{ route('products.create') }}"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-center">
                    Nuevo Producto
                </a>
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
                    <form method="GET" action="{{ route('products.index') }}">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label for="search" class="block text-sm font-medium text-gray-700">Buscar</label>
                                <input type="text" name="search" id="search" value="{{ request('search') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    placeholder="Nombre, modelo, serie...">
                            </div>

                            <div>
                                <label for="brand" class="block text-sm font-medium text-gray-700">Marca</label>
                                <select name="brand" id="brand"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Todas las marcas</option>
                                    @foreach ($brands as $brand)
                                        <option value="{{ $brand->id }}"
                                            {{ request('brand') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="supplier" class="block text-sm font-medium text-gray-700">Proveedor</label>
                                <select name="supplier" id="supplier"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Todos los proveedores</option>
                                    @foreach ($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}"
                                            {{ request('supplier') == $supplier->id ? 'selected' : '' }}>
                                            {{ $supplier->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="flex items-end">
                                <button type="submit"
                                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                                    Filtrar
                                </button>
                                <a href="{{ route('products.index') }}"
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
                                        Imagen</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nombre</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Cantidad</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Clave CB</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Referencia Marca</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($products as $product)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if ($product->image_path)
                                                <div class="cursor-pointer"
                                                    onclick="openTableImageModal('{{ asset('storage/' . $product->image_path) }}')">
                                                    <img src="{{ asset('storage/' . $product->image_path) }}"
                                                        alt="{{ $product->name }}"
                                                        class="h-16 w-16 object-cover rounded"
                                                        title="Clic para ampliar">
                                                </div>
                                            @else
                                                <div
                                                    class="h-16 w-16 bg-gray-100 flex items-center justify-center rounded">
                                                    <span class="text-gray-400 text-xs">Sin imagen</span>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $product->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $product->model ?? 'N/A' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                {{ $product->getTotalQuantityAttribute() }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $product->cb_key ?? 'N/A' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $product->brand_reference ?? 'N/A' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('products.show', $product) }}"
                                                class="text-blue-600 hover:text-blue-900 mr-3">Ver</a>

                                            @if (auth()->user()->hasRole('administrador') || $product->created_by == auth()->id())
                                                <a href="{{ route('products.edit', $product) }}"
                                                    class="text-indigo-600 hover:text-indigo-900 mr-3">Editar</a>
                                            @endif

                                            @if (auth()->user()->hasRole('administrador'))
                                                <form class="inline" action="{{ route('products.destroy', $product) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('¿Estás seguro de eliminar este producto?');">
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
                                            No hay productos registrados.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $products->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<!-- Modal para imágenes en tabla de productos -->
<div id="tableImageModal" class="fixed inset-0 bg-black bg-opacity-90 hidden z-50 overflow-auto">
    <div class="sticky top-0 w-full flex justify-end p-4">
        <button onclick="closeTableImageModal()"
            class="text-white bg-gray-800 rounded-full h-10 w-10 flex items-center justify-center hover:bg-gray-700">
            &times;
        </button>
    </div>
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="max-w-screen-xl max-h-full overflow-auto">
            <img id="tableModalImage" src="" alt="Imagen ampliada" class="max-w-full">
        </div>
    </div>
</div>

<script>
    function openTableImageModal(imageSrc) {
        document.getElementById('tableModalImage').src = imageSrc;
        document.getElementById('tableImageModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeTableImageModal() {
        document.getElementById('tableImageModal').classList.add('hidden');
        document.body.style.overflow = '';
    }
</script>

<!-- Modal para seleccionar campos de exportación -->
<div id="exportModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 overflow-auto">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Exportar productos a CSV</h3>
                    <button type="button" onclick="closeExportModal()" class="text-gray-500 hover:text-gray-800">
                        <span class="text-2xl">&times;</span>
                    </button>
                </div>

                <form action="{{ route('products.export') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <p class="text-sm text-gray-600 mb-2">Seleccione los campos que desea exportar:</p>
                        <div class="grid grid-cols-2 gap-2">
                            <!-- Sección 1: Datos Generales -->
                            <div class="col-span-2 mt-2 mb-1">
                                <span class="font-medium text-gray-700">Datos Generales:</span>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" name="fields[]" value="name" id="field_name"
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                    checked>
                                <label for="field_name" class="ml-2 text-sm text-gray-700">Nombre</label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" name="fields[]" value="model" id="field_model"
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                    checked>
                                <label for="field_model" class="ml-2 text-sm text-gray-700">Modelo</label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" name="fields[]" value="cb_key" id="field_cb_key"
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <label for="field_cb_key" class="ml-2 text-sm text-gray-700">Clave CB</label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" name="fields[]" value="serial_number"
                                    id="field_serial_number"
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <label for="field_serial_number" class="ml-2 text-sm text-gray-700">Número de
                                    Serie</label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" name="fields[]" value="batch" id="field_batch"
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <label for="field_batch" class="ml-2 text-sm text-gray-700">Lote</label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" name="fields[]" value="group" id="field_group"
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <label for="field_group" class="ml-2 text-sm text-gray-700">Grupo</label>
                            </div>

                            <!-- Sección 2: Clasificación y Origen -->
                            <div class="col-span-2 mt-2 mb-1">
                                <span class="font-medium text-gray-700">Clasificación y Origen:</span>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" name="fields[]" value="brand" id="field_brand"
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                    checked>
                                <label for="field_brand" class="ml-2 text-sm text-gray-700">Marca</label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" name="fields[]" value="specialty_area"
                                    id="field_specialty_area"
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <label for="field_specialty_area"
                                    class="ml-2 text-sm text-gray-700">Área/Especialidad</label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" name="fields[]" value="supplier" id="field_supplier"
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                    checked>
                                <label for="field_supplier" class="ml-2 text-sm text-gray-700">Proveedor</label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" name="fields[]" value="brand_reference"
                                    id="field_brand_reference"
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <label for="field_brand_reference" class="ml-2 text-sm text-gray-700">Referencia
                                    Marca</label>
                            </div>

                            <!-- Sección 3: Datos Operativos -->
                            <div class="col-span-2 mt-2 mb-1">
                                <span class="font-medium text-gray-700">Datos Operativos:</span>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" name="fields[]" value="location" id="field_location"
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <label for="field_location" class="ml-2 text-sm text-gray-700">Ubicación</label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" name="fields[]" value="manufacturer_unit"
                                    id="field_manufacturer_unit"
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <label for="field_manufacturer_unit" class="ml-2 text-sm text-gray-700">Unidad de
                                    Medida</label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" name="fields[]" value="freight_company"
                                    id="field_freight_company"
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <label for="field_freight_company" class="ml-2 text-sm text-gray-700">Fletera</label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" name="fields[]" value="freight_cost" id="field_freight_cost"
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <label for="field_freight_cost" class="ml-2 text-sm text-gray-700">Costo de
                                    Flete</label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" name="fields[]" value="expiration_date"
                                    id="field_expiration_date"
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <label for="field_expiration_date" class="ml-2 text-sm text-gray-700">Fecha de
                                    Caducidad</label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" name="fields[]" value="quantity" id="field_quantity"
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                    checked>
                                <label for="field_quantity" class="ml-2 text-sm text-gray-700">Cantidad</label>
                            </div>

                            <!-- Sección 4: Información Adicional -->
                            <div class="col-span-2 mt-2 mb-1">
                                <span class="font-medium text-gray-700">Información Adicional:</span>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" name="fields[]" value="description" id="field_description"
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <label for="field_description" class="ml-2 text-sm text-gray-700">Descripción</label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" name="fields[]" value="created_at" id="field_created_at"
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <label for="field_created_at" class="ml-2 text-sm text-gray-700">Fecha de
                                    Creación</label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" name="fields[]" value="creator" id="field_creator"
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <label for="field_creator" class="ml-2 text-sm text-gray-700">Creado por</label>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-between items-center mt-6">
                        <button type="button" onclick="toggleAllFields()"
                            class="text-sm text-indigo-600 hover:text-indigo-800">
                            Seleccionar/Deseleccionar todos
                        </button>
                        <button type="submit"
                            class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Exportar
                        </button>
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
