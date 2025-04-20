<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Crear Nuevo Producto') }}
        </h2>
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
                                    <select id="brand_id" name="brand_id"
                                        class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="">Seleccionar marca</option>
                                        @foreach ($brands as $brand)
                                            <option value="{{ $brand->id }}"
                                                {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                                {{ $brand->name }}</option>
                                        @endforeach
                                    </select>
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
                                    <select id="supplier_id" name="supplier_id"
                                        class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="">Seleccionar proveedor</option>
                                        @foreach ($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}"
                                                {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                                {{ $supplier->name }}</option>
                                        @endforeach
                                    </select>
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
    </script>
</x-app-layout>
