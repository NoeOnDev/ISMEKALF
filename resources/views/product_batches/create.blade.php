<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Agregar Inventario a') }}: {{ $product->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('products.batches.store', $product) }}">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Número de lote -->
                            <div>
                                <x-input-label for="batch_number" :value="__('Número de Lote')" />
                                <x-text-input id="batch_number" class="block mt-1 w-full" type="text"
                                    name="batch_number" :value="old('batch_number')" />
                                <x-input-error :messages="$errors->get('batch_number')" class="mt-2" />
                            </div>

                            <!-- Fecha de caducidad -->
                            <div>
                                <x-input-label for="expiration_date" :value="__('Fecha de Caducidad')" />
                                <x-text-input id="expiration_date" class="block mt-1 w-full" type="date"
                                    name="expiration_date" :value="old('expiration_date')" />
                                <x-input-error :messages="$errors->get('expiration_date')" class="mt-2" />
                            </div>

                            <!-- Cantidad -->
                            <div>
                                <x-input-label for="quantity" :value="__('Cantidad')" />
                                <x-text-input id="quantity" class="block mt-1 w-full" type="number" name="quantity"
                                    :value="old('quantity', 1)" min="1" required />
                                <x-input-error :messages="$errors->get('quantity')" class="mt-2" />
                            </div>

                            <!-- Ubicación -->
                            <div>
                                <x-input-label for="location" :value="__('Ubicación')" />
                                <x-text-input id="location" class="block mt-1 w-full" type="text" name="location"
                                    :value="old('location', $product->location)" />
                                <x-input-error :messages="$errors->get('location')" class="mt-2" />
                            </div>

                            <!-- Notas -->
                            <div class="md:col-span-2">
                                <x-input-label for="notes" :value="__('Notas')" />
                                <textarea id="notes" name="notes" rows="3"
                                    class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('notes') }}</textarea>
                                <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('products.show', $product) }}"
                                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                Cancelar
                            </a>
                            <x-primary-button class="ms-4">
                                {{ __('Agregar Inventario') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
