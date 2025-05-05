<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-4 md:mb-0">
                {{ __('Gestión de Usuarios') }}
            </h2>
            <div class="flex flex-col sm:flex-row sm:space-x-2 space-y-2 sm:space-y-0 w-full md:w-auto md:ml-auto">
                <a href="{{ route('admin.users.create') }}"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-center">
                    Crear Usuario
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
                    <form method="GET" action="{{ route('admin.users') }}">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="search" class="block text-sm font-medium text-gray-700">Buscar</label>
                                <input type="text" name="search" id="search" value="{{ request('search') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    placeholder="Nombre, email...">
                            </div>

                            <div>
                                <label for="role" class="block text-sm font-medium text-gray-700">Rol</label>
                                <select name="role" id="role"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Todos los roles</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->name }}"
                                            {{ request('role') == $role->name ? 'selected' : '' }}>{{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="flex items-end">
                                <button type="submit"
                                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                                    Filtrar
                                </button>
                                <a href="{{ route('admin.users') }}"
                                    class="ml-2 bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                                    Limpiar
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tabla de usuarios -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nombre
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Email
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Rol
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Fecha de registro
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Estado
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($users as $user)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                @foreach ($user->roles as $role)
                                                    <span
                                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                        {{ $role->name }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-500">{{ $user->created_at->format('d/m/Y') }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($user->active)
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Activo
                                                </span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    Bloqueado
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('admin.users.edit', $user) }}"
                                                class="text-indigo-600 hover:text-indigo-900 mr-3">Editar</a>

                                            @if ($user->id !== auth()->id())
                                                @if($user->active)
                                                    <form class="inline" action="{{ route('admin.users.block', $user) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit" class="text-yellow-600 hover:text-yellow-900 mr-3">Bloquear</button>
                                                    </form>
                                                @else
                                                    <form class="inline" action="{{ route('admin.users.unblock', $user) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit" class="text-green-600 hover:text-green-900 mr-3">Desbloquear</button>
                                                    </form>
                                                @endif

                                                <form class="inline" action="{{ route('admin.users.destroy', $user) }}" method="POST"
                                                    onsubmit="return confirm('¿Estás seguro de eliminar este usuario?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900">Eliminar</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6"
                                            class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                                            No hay usuarios registrados.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
