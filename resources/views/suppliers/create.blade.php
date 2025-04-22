<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-4 md:mb-0">
                {{ __('Crear Nuevo Proveedor') }}
            </h2>
            <div class="flex flex-col sm:flex-row sm:space-x-2 space-y-2 sm:space-y-0 w-full md:w-auto md:ml-auto">
                <a href="{{ route('suppliers.index') }}"
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
                    <form method="POST" action="{{ route('suppliers.store') }}">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nombre -->
                            <div>
                                <x-input-label for="name" :value="__('Nombre')" />
                                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                                    :value="old('name')" required autofocus />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <!-- Nombre del Contacto -->
                            <div>
                                <x-input-label for="contact_name" :value="__('Nombre del Contacto')" />
                                <x-text-input id="contact_name" class="block mt-1 w-full" type="text"
                                    name="contact_name" :value="old('contact_name')" />
                                <x-input-error :messages="$errors->get('contact_name')" class="mt-2" />
                            </div>

                            <!-- Email -->
                            <div>
                                <x-input-label for="email" :value="__('Email')" />
                                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                                    :value="old('email')" />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>

                            <!-- Teléfono -->
                            <div>
                                <x-input-label for="phone" :value="__('Teléfono')" />
                                <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone"
                                    :value="old('phone')" />
                                <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                            </div>

                            <!-- Dirección -->
                            <div>
                                <x-input-label for="address" :value="__('Dirección')" />
                                <x-text-input id="address" class="block mt-1 w-full" type="text" name="address"
                                    :value="old('address')" />
                                <x-input-error :messages="$errors->get('address')" class="mt-2" />
                            </div>

                            <!-- Ciudad -->
                            <div>
                                <x-input-label for="city" :value="__('Ciudad')" />
                                <x-text-input id="city" class="block mt-1 w-full" type="text" name="city"
                                    :value="old('city')" />
                                <x-input-error :messages="$errors->get('city')" class="mt-2" />
                            </div>

                            <!-- País -->
                            <div>
                                <x-input-label for="country" :value="__('País')" />
                                <x-text-input id="country" class="block mt-1 w-full" type="text" name="country"
                                    :value="old('country')" />
                                <x-input-error :messages="$errors->get('country')" class="mt-2" />
                            </div>

                            <!-- Código Postal -->
                            <div>
                                <x-input-label for="postal_code" :value="__('Código Postal')" />
                                <x-text-input id="postal_code" class="block mt-1 w-full" type="text"
                                    name="postal_code" :value="old('postal_code')" />
                                <x-input-error :messages="$errors->get('postal_code')" class="mt-2" />
                            </div>

                            <!-- Notas -->
                            <div class="col-span-1 md:col-span-2">
                                <x-input-label for="notes" :value="__('Notas')" />
                                <textarea id="notes" name="notes" rows="3"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('notes') }}</textarea>
                                <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                            </div>

                            <!-- Activo -->
                            <div class="col-span-1 md:col-span-2">
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="active" value="1" checked
                                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <span class="ml-2">Activo</span>
                                </label>
                            </div>
                        </div>

                        <div class="flex justify-end mt-6">
                            <x-secondary-button onclick="window.history.back()" class="mr-2">
                                {{ __('Cancelar') }}
                            </x-secondary-button>
                            <x-primary-button>
                                {{ __('Guardar') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
