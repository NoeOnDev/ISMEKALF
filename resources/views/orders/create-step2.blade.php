<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Nueva Remisión - Paso 2: Información del Cliente') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('error'))
                <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4" role="alert">
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Resumen de Productos Seleccionados</h3>

                    <div class="overflow-x-auto mb-6">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Producto</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cantidad</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($cart as $item)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $item['name'] }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $item['quantity'] }}</div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Información del Cliente</h3>

                    <form method="POST" action="{{ route('orders.store') }}">
                        @csrf

                        <!-- Modificar la sección del selector de clientes para añadir el botón -->
                        <div class="mb-4">
                            <label for="client_id" class="block text-sm font-medium text-gray-700">Seleccionar Cliente</label>
                            <div class="flex">
                                <select id="client_id" name="client_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 rounded-r-none">
                                    <option value="">Seleccione un cliente</option>
                                    @foreach($clients as $client)
                                        <option value="{{ $client->id }}" {{ (old('client_id', $existingClient?->id) == $client->id) ? 'selected' : '' }}>
                                            {{ $client->name }} ({{ $client->code }})
                                        </option>
                                    @endforeach
                                </select>
                                <button type="button" onclick="openClientModal()" class="mt-1 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-l-none">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                            <x-input-error :messages="$errors->get('client_id')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <label for="notes" class="block text-sm font-medium text-gray-700">Notas</label>
                            <textarea id="notes" name="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('notes') }}</textarea>
                            <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                        </div>

                        <div class="flex justify-between mt-6">
                            <a href="{{ route('orders.create.step1') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                &laquo; Anterior
                            </a>
                            <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                Finalizar Remisión
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para crear cliente rápido -->
    <div id="clientModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 overflow-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Crear Nuevo Cliente</h3>
                        <button type="button" onclick="closeClientModal()" class="text-gray-500 hover:text-gray-800">
                            <span class="text-2xl">&times;</span>
                        </button>
                    </div>

                    <form id="clientForm">
                        @csrf
                        <div class="mb-4">
                            <x-input-label for="client_name" value="Nombre" />
                            <x-text-input id="client_name" class="block mt-1 w-full" type="text" name="name" required />
                            <div id="client_name_error" class="text-red-600 text-sm mt-1 hidden"></div>
                        </div>

                        <div class="mb-4">
                            <x-input-label for="client_code" value="Código" />
                            <x-text-input id="client_code" class="block mt-1 w-full" type="text" name="code" required />
                            <div id="client_code_error" class="text-red-600 text-sm mt-1 hidden"></div>
                        </div>

                        <div class="mb-4">
                            <x-input-label for="client_contact" value="Contacto" />
                            <x-text-input id="client_contact" class="block mt-1 w-full" type="text" name="contact" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="client_phone" value="Teléfono" />
                            <x-text-input id="client_phone" class="block mt-1 w-full" type="text" name="phone" />
                        </div>

                        <div class="flex justify-end mt-6">
                            <button type="button" onclick="closeClientModal()" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2">
                                Cancelar
                            </button>
                            <button type="button" id="saveClientBtn" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Guardar Cliente
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Funciones para modal de clientes
        function openClientModal() {
            document.getElementById('clientModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeClientModal() {
            document.getElementById('clientModal').classList.add('hidden');
            document.body.style.overflow = '';
            document.getElementById('client_name').value = '';
            document.getElementById('client_code').value = '';
            document.getElementById('client_contact').value = '';
            document.getElementById('client_phone').value = '';
            document.getElementById('client_name_error').classList.add('hidden');
            document.getElementById('client_name_error').textContent = '';
            document.getElementById('client_code_error').classList.add('hidden');
            document.getElementById('client_code_error').textContent = '';
        }

        // Función para crear un cliente por Ajax
        document.getElementById('saveClientBtn').addEventListener('click', function() {
            const clientName = document.getElementById('client_name').value;
            const clientCode = document.getElementById('client_code').value;
            const clientContact = document.getElementById('client_contact').value;
            const clientPhone = document.getElementById('client_phone').value;
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch('/api/clients', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify({
                    name: clientName,
                    code: clientCode,
                    contact: clientContact,
                    phone: clientPhone
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    // Si el error contiene la palabra "code", es un error de código duplicado
                    if (data.error.toLowerCase().includes('code')) {
                        document.getElementById('client_code_error').textContent = data.error;
                        document.getElementById('client_code_error').classList.remove('hidden');
                    } else {
                        document.getElementById('client_name_error').textContent = data.error;
                        document.getElementById('client_name_error').classList.remove('hidden');
                    }
                    return;
                }

                // Añadir el nuevo cliente al select
                const clientSelect = document.getElementById('client_id');
                const newOption = document.createElement('option');
                newOption.value = data.id;
                newOption.text = data.name + ' (' + data.code + ')';
                newOption.selected = true;
                clientSelect.add(newOption);

                // Mostrar mensaje de éxito y cerrar el modal
                closeClientModal();

                // Opcional: Mostrar una notificación de éxito
                alert('Cliente creado con éxito');
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    </script>
</x-app-layout>
