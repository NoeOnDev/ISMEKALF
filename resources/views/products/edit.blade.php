<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Editar Producto') }}
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
                    <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-6">
                            <h3 class="text-lg font-medium mb-4 pb-2 border-b">Sección 1: Datos Generales y de
                                Identificación</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label for="name" value="Nombre del Producto" />
                                    <input type="text" name="name" id="name"
                                        value="{{ old('name', $product->name) }}" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="model" value="Modelo" />
                                    <input type="text" name="model" id="model"
                                        value="{{ old('model', $product->model) }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <x-input-error :messages="$errors->get('model')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="cb_key" value="Clave CB" />
                                    <input type="text" name="cb_key" id="cb_key"
                                        value="{{ old('cb_key', $product->cb_key) }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <x-input-error :messages="$errors->get('cb_key')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="serial_number" value="Número de Serie" />
                                    <input type="text" name="serial_number" id="serial_number"
                                        value="{{ old('serial_number', $product->serial_number) }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <x-input-error :messages="$errors->get('serial_number')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="batch" value="Lote" />
                                    <input type="text" name="batch" id="batch"
                                        value="{{ old('batch', $product->batch) }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <x-input-error :messages="$errors->get('batch')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="group" value="Grupo" />
                                    <input type="text" name="group" id="group"
                                        value="{{ old('group', $product->group) }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <x-input-error :messages="$errors->get('group')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <div class="mb-6">
                            <h3 class="text-lg font-medium mb-4 pb-2 border-b">Sección 2: Datos de Clasificación y
                                Origen</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label for="brand_id" value="Marca" />
                                    <select name="brand_id" id="brand_id"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="">Seleccione una marca</option>
                                        @foreach ($brands as $brand)
                                            <option value="{{ $brand->id }}"
                                                {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>
                                                {{ $brand->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('brand_id')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="specialty_area" value="Área/Especialidad" />
                                    <input type="text" name="specialty_area" id="specialty_area"
                                        value="{{ old('specialty_area', $product->specialty_area) }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <x-input-error :messages="$errors->get('specialty_area')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="supplier_id" value="Proveedor" />
                                    <select name="supplier_id" id="supplier_id"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="">Seleccione un proveedor</option>
                                        @foreach ($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}"
                                                {{ old('supplier_id', $product->supplier_id) == $supplier->id ? 'selected' : '' }}>
                                                {{ $supplier->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('supplier_id')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="brand_reference" value="Referencia de la Marca" />
                                    <input type="text" name="brand_reference" id="brand_reference"
                                        value="{{ old('brand_reference', $product->brand_reference) }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <x-input-error :messages="$errors->get('brand_reference')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <div class="mb-6">
                            <h3 class="text-lg font-medium mb-4 pb-2 border-b">Sección 3: Datos Operativos y Adicionales
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label for="location" value="Ubicación" />
                                    <input type="text" name="location" id="location"
                                        value="{{ old('location', $product->location) }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <x-input-error :messages="$errors->get('location')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="manufacturer_unit" value="Unidad de Medida del Fabricante" />
                                    <input type="text" name="manufacturer_unit" id="manufacturer_unit"
                                        value="{{ old('manufacturer_unit', $product->manufacturer_unit) }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <x-input-error :messages="$errors->get('manufacturer_unit')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="freight_company" value="Empresa de Flete" />
                                    <input type="text" name="freight_company" id="freight_company"
                                        value="{{ old('freight_company', $product->freight_company) }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <x-input-error :messages="$errors->get('freight_company')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="freight_cost" value="Costo de Flete" />
                                    <input type="number" step="0.01" min="0" name="freight_cost"
                                        id="freight_cost" value="{{ old('freight_cost', $product->freight_cost) }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <x-input-error :messages="$errors->get('freight_cost')" class="mt-2" />
                                </div>

                                <div class="md:col-span-2">
                                    <x-input-label for="description" value="Descripción" />
                                    <textarea name="description" id="description" rows="3"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $product->description) }}</textarea>
                                    <x-input-error :messages="$errors->get('description')" class="mt-2" />
                                </div>

                                <div class="md:col-span-2">
                                    <x-input-label for="image" value="Imagen del Producto" />
                                    <input type="file" name="image" id="image" accept="image/*"
                                        onchange="previewImage(this)" class="mt-1 block w-full text-gray-700">
                                    <x-input-error :messages="$errors->get('image')" class="mt-2" />

                                    <div id="image-preview" class="mt-2 hidden">
                                        <p class="text-sm text-gray-500 mb-1">Vista previa:</p>
                                        <img id="preview-img" src="" alt="Vista previa"
                                            class="h-32 w-auto rounded border">
                                    </div>

                                    @if ($product->image_path)
                                        <div class="mt-2">
                                            <p class="text-sm text-gray-500 mb-1">Imagen actual:</p>
                                            <img src="{{ asset('storage/' . $product->image_path) }}"
                                                alt="{{ $product->name }}" class="h-32 w-auto rounded border">
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-between mt-8">
                            <a href="{{ route('products.show', $product) }}"
                                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Cancelar
                            </a>
                            <button type="submit"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Actualizar Producto
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
    </script>
</x-app-layout>
