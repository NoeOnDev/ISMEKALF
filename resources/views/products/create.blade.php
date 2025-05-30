<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-4 md:mb-0">
                {{ __('Crear Nuevo Producto') }}
            </h2>
            <div class="flex flex-col sm:flex-row sm:space-x-2 space-y-2 sm:space-y-0 w-full md:w-auto md:ml-auto">
                <a href="{{ route('products.index') }}"
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

                    <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data"
                        x-data="{ activeTab: 1 }">
                        @csrf

                        <!-- Tabs -->
                        <div class="mb-6 border-b border-gray-200">
                            <ul class="flex flex-wrap -mb-px text-sm font-medium text-center">
                                <li class="mr-2">
                                    <button type="button" class="inline-block p-4"
                                        :class="activeTab === 1 ? 'text-blue-600 border-b-2 border-blue-600' :
                                            'hover:text-gray-600 hover:border-gray-300'"
                                        @click="activeTab = 1">
                                        Datos Generales
                                    </button>
                                </li>
                                <li class="mr-2">
                                    <button type="button" class="inline-block p-4"
                                        :class="activeTab === 2 ? 'text-blue-600 border-b-2 border-blue-600' :
                                            'hover:text-gray-600 hover:border-gray-300'"
                                        @click="activeTab = 2">
                                        Clasificación y Origen
                                    </button>
                                </li>
                                <li class="mr-2">
                                    <button type="button" class="inline-block p-4"
                                        :class="activeTab === 3 ? 'text-blue-600 border-b-2 border-blue-600' :
                                            'hover:text-gray-600 hover:border-gray-300'"
                                        @click="activeTab = 3">
                                        Datos Operativos
                                    </button>
                                </li>
                            </ul>
                        </div>

                        <!-- Sección 1: Datos Generales -->
                        <div x-show="activeTab === 1" class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Nombre -->
                                <div>
                                    <x-input-label for="name" :value="__('Nombre')" />
                                    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                                        :value="old('name')" required autofocus />
                                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                </div>

                                <!-- Modelo -->
                                <div>
                                    <x-input-label for="model" :value="__('Modelo')" />
                                    <x-text-input id="model" class="block mt-1 w-full" type="text" name="model"
                                        :value="old('model')" />
                                    <x-input-error :messages="$errors->get('model')" class="mt-2" />
                                </div>

                                <!-- Clave CB -->
                                <div>
                                    <x-input-label for="cb_key" :value="__('Clave CB')" />
                                    <x-text-input id="cb_key" class="block mt-1 w-full" type="text" name="cb_key"
                                        :value="old('cb_key')" />
                                    <x-input-error :messages="$errors->get('cb_key')" class="mt-2" />
                                </div>

                                <!-- Número de Serie -->
                                <div>
                                    <x-input-label for="serial_number" :value="__('Número de Serie')" />
                                    <x-text-input id="serial_number" class="block mt-1 w-full" type="text"
                                        name="serial_number" :value="old('serial_number')" />
                                    <x-input-error :messages="$errors->get('serial_number')" class="mt-2" />
                                </div>

                                <!-- Lote -->
                                <div>
                                    <x-input-label for="batch" :value="__('Lote')" />
                                    <x-text-input id="batch" class="block mt-1 w-full" type="text" name="batch"
                                        :value="old('batch')" />
                                    <x-input-error :messages="$errors->get('batch')" class="mt-2" />
                                </div>

                                <!-- Grupo -->
                                <div>
                                    <x-input-label for="group" :value="__('Grupo')" />
                                    <x-text-input id="group" class="block mt-1 w-full" type="text" name="group"
                                        :value="old('group')" />
                                    <x-input-error :messages="$errors->get('group')" class="mt-2" />
                                </div>
                            </div>

                            <div class="flex justify-end mt-6">
                                <x-secondary-button type="button" @click="activeTab = 2" class="ml-3">
                                    {{ __('Siguiente') }} &raquo;
                                </x-secondary-button>
                            </div>
                        </div>

                        <!-- Sección 2: Clasificación y Origen -->
                        <div x-show="activeTab === 2" class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Marca -->
                                <div>
                                    <x-input-label for="brand_id" :value="__('Marca')" />
                                    <div class="flex">
                                        <select id="brand_id" name="brand_id"
                                            class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 rounded-r-none">
                                            <option value="">Seleccionar marca</option>
                                            @foreach ($brands as $brand)
                                                <option value="{{ $brand->id }}"
                                                    {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                                    {{ $brand->name }}</option>
                                            @endforeach
                                        </select>
                                        <button type="button" onclick="openBrandModal()"
                                            class="mt-1 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-l-none">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                    <x-input-error :messages="$errors->get('brand_id')" class="mt-2" />
                                </div>

                                <!-- Área/Especialidad -->
                                <div>
                                    <x-input-label for="specialty_area" :value="__('Área/Especialidad')" />
                                    <x-text-input id="specialty_area" class="block mt-1 w-full" type="text"
                                        name="specialty_area" :value="old('specialty_area')" />
                                    <x-input-error :messages="$errors->get('specialty_area')" class="mt-2" />
                                </div>

                                <!-- Proveedor -->
                                <div>
                                    <x-input-label for="supplier_id" :value="__('Proveedor')" />
                                    <div class="flex">
                                        <select id="supplier_id" name="supplier_id"
                                            class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 rounded-r-none">
                                            <option value="">Seleccionar proveedor</option>
                                            @foreach ($suppliers as $supplier)
                                                <option value="{{ $supplier->id }}"
                                                    {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                                    {{ $supplier->name }}</option>
                                            @endforeach
                                        </select>
                                        <button type="button" onclick="openSupplierModal()"
                                            class="mt-1 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-l-none">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                    <x-input-error :messages="$errors->get('supplier_id')" class="mt-2" />
                                </div>

                                <!-- Referencia Marca -->
                                <div>
                                    <x-input-label for="brand_reference" :value="__('Referencia Marca')" />
                                    <x-text-input id="brand_reference" class="block mt-1 w-full" type="text"
                                        name="brand_reference" :value="old('brand_reference')" />
                                    <x-input-error :messages="$errors->get('brand_reference')" class="mt-2" />
                                </div>
                            </div>

                            <div class="flex justify-between mt-6">
                                <x-secondary-button type="button" @click="activeTab = 1">
                                    &laquo; {{ __('Anterior') }}
                                </x-secondary-button>
                                <x-secondary-button type="button" @click="activeTab = 3" class="ml-3">
                                    {{ __('Siguiente') }} &raquo;
                                </x-secondary-button>
                            </div>
                        </div>

                        <!-- Sección 3: Datos Operativos -->
                        <div x-show="activeTab === 3" class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Ubicación -->
                                <div>
                                    <x-input-label for="location" :value="__('Ubicación')" />
                                    <x-text-input id="location" class="block mt-1 w-full" type="text"
                                        name="location" :value="old('location')" />
                                    <x-input-error :messages="$errors->get('location')" class="mt-2" />
                                </div>

                                <!-- Unidad de Medida Fabricante -->
                                <div>
                                    <x-input-label for="manufacturer_unit" :value="__('Unidad de Medida')" />
                                    <x-text-input id="manufacturer_unit" class="block mt-1 w-full" type="text"
                                        name="manufacturer_unit" :value="old('manufacturer_unit')" />
                                    <x-input-error :messages="$errors->get('manufacturer_unit')" class="mt-2" />
                                </div>

                                <!-- Fletera -->
                                <div>
                                    <x-input-label for="freight_company" :value="__('Fletera')" />
                                    <x-text-input id="freight_company" class="block mt-1 w-full" type="text"
                                        name="freight_company" :value="old('freight_company')" />
                                    <x-input-error :messages="$errors->get('freight_company')" class="mt-2" />
                                </div>

                                <!-- Costo de Fletera -->
                                <div>
                                    <x-input-label for="freight_cost" :value="__('Costo de Fletera')" />
                                    <x-text-input id="freight_cost" class="block mt-1 w-full" type="number"
                                        step="0.01" name="freight_cost" :value="old('freight_cost')" />
                                    <x-input-error :messages="$errors->get('freight_cost')" class="mt-2" />
                                </div>
                            </div>

                            <!-- Agregar después de los campos de datos operativos -->
                            <div class="col-span-2 mt-4 p-4 bg-blue-50 border-l-4 border-blue-500 text-blue-700">
                                <p><strong>Nota:</strong> La cantidad y fecha de caducidad se establecerán al agregar
                                    inventario al producto después de crearlo.</p>
                            </div>

                            <!-- Descripción -->
                            <div>
                                <x-input-label for="description" :value="__('Descripción')" />
                                <textarea id="description" name="description" rows="3"
                                    class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description') }}</textarea>
                                <x-input-error :messages="$errors->get('description')" class="mt-2" />
                            </div>

                            <!-- Imagen del Producto -->
                            <div>
                                <x-input-label for="image" :value="__('Imagen del Producto')" />
                                <input id="image" class="block mt-1 w-full border border-gray-300 p-2 rounded"
                                    type="file" name="image" accept="image/*" onchange="previewImage(this)" />

                                <div id="image-preview" class="mt-2 hidden">
                                    <p class="text-sm text-gray-500 mb-1">Vista previa:</p>
                                    <img id="preview-img" src="" alt="Vista previa"
                                        class="h-32 w-auto rounded border">
                                </div>

                                <x-input-error :messages="$errors->get('image')" class="mt-2" />
                            </div>

                            <div class="flex justify-between mt-6">
                                <x-secondary-button type="button" @click="activeTab = 2">
                                    &laquo; {{ __('Anterior') }}
                                </x-secondary-button>
                                <x-primary-button class="ml-3">
                                    {{ __('Guardar Producto') }}
                                </x-primary-button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para crear marca -->
    <div id="brandModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 overflow-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Crear Nueva Marca</h3>
                        <button type="button" onclick="closeBrandModal()" class="text-gray-500 hover:text-gray-800">
                            <span class="text-2xl">&times;</span>
                        </button>
                    </div>

                    <form id="brandForm">
                        @csrf
                        <div class="mb-4">
                            <x-input-label for="brand_name" value="Nombre de la Marca" />
                            <x-text-input id="brand_name" class="block mt-1 w-full" type="text" name="name"
                                required />
                            <div id="brand_name_error" class="text-red-600 text-sm mt-1 hidden"></div>
                        </div>

                        <div class="mb-4">
                            <x-input-label for="brand_description" value="Descripción" />
                            <textarea id="brand_description" name="description"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                rows="3"></textarea>
                        </div>

                        <div class="flex justify-end mt-6">
                            <button type="button" onclick="closeBrandModal()"
                                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2">
                                Cancelar
                            </button>
                            <button type="button" id="saveBrandBtn"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Guardar Marca
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para crear proveedor -->
    <div id="supplierModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 overflow-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Crear Nuevo Proveedor</h3>
                        <button type="button" onclick="closeSupplierModal()"
                            class="text-gray-500 hover:text-gray-800">
                            <span class="text-2xl">&times;</span>
                        </button>
                    </div>

                    <form id="supplierForm">
                        @csrf
                        <div class="mb-4">
                            <x-input-label for="supplier_name" value="Nombre del Proveedor" />
                            <x-text-input id="supplier_name" class="block mt-1 w-full" type="text" name="name"
                                required />
                            <div id="supplier_name_error" class="text-red-600 text-sm mt-1 hidden"></div>
                        </div>

                        <div class="mb-4">
                            <x-input-label for="supplier_contact_name" value="Nombre de Contacto" />
                            <x-text-input id="supplier_contact_name" class="block mt-1 w-full" type="text"
                                name="contact_name" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="supplier_email" value="Correo Electrónico" />
                            <x-text-input id="supplier_email" class="block mt-1 w-full" type="email"
                                name="email" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="supplier_phone" value="Teléfono" />
                            <x-text-input id="supplier_phone" class="block mt-1 w-full" type="text"
                                name="phone" />
                        </div>

                        <div class="flex justify-end mt-6">
                            <button type="button" onclick="closeSupplierModal()"
                                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2">
                                Cancelar
                            </button>
                            <button type="button" id="saveSupplierBtn"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Guardar Proveedor
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function previewImage(input) {
            const preview = document.getElementById('image-preview');
            const previewImg = document.getElementById('preview-img');

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    preview.classList.remove('hidden');
                }

                reader.readAsDataURL(input.files[0]);
            } else {
                previewImg.src = '';
                preview.classList.add('hidden');
            }
        }

        // Funciones para modal de marcas
        function openBrandModal() {
            document.getElementById('brandModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeBrandModal() {
            document.getElementById('brandModal').classList.add('hidden');
            document.body.style.overflow = '';
            document.getElementById('brand_name').value = '';
            document.getElementById('brand_description').value = '';
            document.getElementById('brand_name_error').classList.add('hidden');
            document.getElementById('brand_name_error').textContent = '';
        }

        // Funciones para modal de proveedores
        function openSupplierModal() {
            document.getElementById('supplierModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeSupplierModal() {
            document.getElementById('supplierModal').classList.add('hidden');
            document.body.style.overflow = '';
            document.getElementById('supplier_name').value = '';
            document.getElementById('supplier_contact_name').value = '';
            document.getElementById('supplier_email').value = '';
            document.getElementById('supplier_phone').value = '';
            document.getElementById('supplier_name_error').classList.add('hidden');
            document.getElementById('supplier_name_error').textContent = '';
        }

        // Función para crear una marca por Ajax
        document.getElementById('saveBrandBtn').addEventListener('click', function() {
            const brandName = document.getElementById('brand_name').value;
            const brandDescription = document.getElementById('brand_description').value;
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch('/api/brands', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token
                    },
                    body: JSON.stringify({
                        name: brandName,
                        description: brandDescription,
                        active: true
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        document.getElementById('brand_name_error').textContent = data.error;
                        document.getElementById('brand_name_error').classList.remove('hidden');
                        return;
                    }

                    // Añadir la nueva marca al select
                    const brandSelect = document.getElementById('brand_id');
                    const newOption = document.createElement('option');
                    newOption.value = data.id;
                    newOption.text = data.name;
                    newOption.selected = true;
                    brandSelect.add(newOption);

                    // Mostrar mensaje de éxito y cerrar el modal
                    closeBrandModal();

                    // Opcional: Mostrar una notificación de éxito
                    alert('Marca creada con éxito');
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        });

        // Función para crear un proveedor por Ajax
        document.getElementById('saveSupplierBtn').addEventListener('click', function() {
            const supplierName = document.getElementById('supplier_name').value;
            const contactName = document.getElementById('supplier_contact_name').value;
            const email = document.getElementById('supplier_email').value;
            const phone = document.getElementById('supplier_phone').value;
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch('/api/suppliers', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token
                    },
                    body: JSON.stringify({
                        name: supplierName,
                        contact_name: contactName,
                        email: email,
                        phone: phone,
                        active: true
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        document.getElementById('supplier_name_error').textContent = data.error;
                        document.getElementById('supplier_name_error').classList.remove('hidden');
                        return;
                    }

                    // Añadir el nuevo proveedor al select
                    const supplierSelect = document.getElementById('supplier_id');
                    const newOption = document.createElement('option');
                    newOption.value = data.id;
                    newOption.text = data.name;
                    newOption.selected = true;
                    supplierSelect.add(newOption);

                    // Mostrar mensaje de éxito y cerrar el modal
                    closeSupplierModal();

                    // Opcional: Mostrar una notificación de éxito
                    alert('Proveedor creado con éxito');
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        });
    </script>
</x-app-layout>
