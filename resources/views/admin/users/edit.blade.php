<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-4 md:mb-0">
                {{ __('Editar Usuario') }}
            </h2>
            <div class="flex flex-col sm:flex-row sm:space-x-2 space-y-2 sm:space-y-0 w-full md:w-auto md:ml-auto">
                <a href="{{ route('admin.users') }}"
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
                    <form action="{{ route('admin.users.update', $user) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="space-y-4">
                            <!-- Nombre -->
                            <div>
                                <x-input-label for="name" :value="__('Nombre')" />
                                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                                    :value="old('name', $user->name)" required />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <!-- Email -->
                            <div>
                                <x-input-label for="email" :value="__('Email')" />
                                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                                    :value="old('email', $user->email)" required />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>

                            <!-- Roles -->
                            <div>
                                <x-input-label for="roles" :value="__('Rol')" />
                                <div class="mt-2 space-y-2">
                                    @foreach ($roles as $role)
                                        <div class="flex items-center">
                                            <input type="radio" id="role_{{ $role->id }}" name="role"
                                                value="{{ $role->name }}"
                                                class="text-indigo-600 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                                {{ $user->hasRole($role->name) ? 'checked' : '' }}
                                                {{ $user->id === auth()->id() && $user->hasRole('administrador') && $role->name !== 'administrador' ? 'disabled' : '' }}
                                                required>
                                            <label for="role_{{ $role->id }}"
                                                class="ml-2 text-gray-700 {{ $user->id === auth()->id() && $user->hasRole('administrador') && $role->name !== 'administrador' ? 'text-gray-400' : '' }}">
                                                {{ ucfirst($role->name) }}
                                                @if ($user->id === auth()->id() && $user->hasRole('administrador') && $role->name !== 'administrador')
                                                    <span class="text-xs text-red-500">(No puedes cambiar tu rol de
                                                        administrador)</span>
                                                @endif
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                                <x-input-error :messages="$errors->get('role')" class="mt-2" />
                            </div>

                            <div class="flex items-center justify-end mt-4">
                                <x-primary-button class="ml-4">
                                    {{ __('Actualizar') }}
                                </x-primary-button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
