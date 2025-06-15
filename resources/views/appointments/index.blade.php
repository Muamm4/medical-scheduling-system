<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
                {{ __('Agendamentos') }}
            </h2>
            <div>
                <a href="{{ route('appointments.create') }}"
                    class="focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <x-button>
                        {{ __('Novo Agendamento') }}
                    </x-button>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-200 px-4 py-3 rounded relative mb-4"
                    role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-100 dark:bg-red-900 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-200 px-4 py-3 rounded relative mb-4"
                    role="alert">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Formulário de filtros para agendamentos -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Filtrar Agendamentos</h3>
                    <form action="{{ route('appointments.index') }}" method="get" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="start_date"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Data
                                    Inicial</label>
                                <input type="date" name="start_date" id="start_date"
                                    value="{{ request('start_date') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            <div>
                                <label for="end_date"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Data
                                    Final</label>
                                <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            <div>
                                <label for="status"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                                <select name="status" id="status"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Todos</option>
                                    @foreach ($statusOptions as $value => $label)
                                        <option value="{{ $value }}" {{ request('status') == $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="doctor"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Médico</label>
                                <input type="text" name="doctor" id="doctor" value="{{ request('doctor') }}"
                                    placeholder="Nome do médico"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            <div>
                                <label for="specialty"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Especialidade</label>
                                <input type="text" name="specialty" id="specialty"
                                    value="{{ request('specialty') }}" placeholder="Especialidade"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                        </div>

                        <div class="flex justify-between">
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                Filtrar
                            </button>

                            <a href="{{ route('appointments.exportCsv', request()->query()) }}"
                                class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Exportar CSV
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-900 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if (count($appointments) > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 sm:rounded-lg">
                                <thead class="bg-gray-100 dark:bg-gray-700">
                                    <tr>
                                        <th
                                            class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Paciente</th>
                                        <th
                                            class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Médico</th>
                                        <th
                                            class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Especialidade</th>
                                        <th
                                            class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Data/Hora</th>
                                        <th
                                            class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Status</th>
                                        <th
                                            class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Ações</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach ($appointments as $appointment)
                                        <tr>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">
                                                {{ $appointment->patient->name }}</td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">
                                                {{ $appointment->doctor_name }}</td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">
                                                {{ $appointment->specialty }}</td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">
                                                {{ $appointment->appointment_datetime->format('d/m/Y H:i') }}</td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">
                                                <span
                                                    class="px-2 py-1 rounded text-xs
                                                    @if ($appointment->status->value === 1) bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                                    @elseif($appointment->status->value === 2) bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                                    @else bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 @endif">
                                                    {{ $appointment->status->label() }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex space-x-2">
                                                    <a href="{{ route('appointments.show', $appointment->id) }}"
                                                        class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                        </svg>
                                                    </a>

                                                    @if ($appointment->status->value === 1 && $appointment->canBeCanceled())
                                                        <form
                                                            action="{{ route('appointments.cancel', $appointment->id) }}"
                                                            method="POST" class="inline">
                                                            @csrf
                                                            <button type="submit"
                                                                class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                                                                onclick="return confirm('Tem certeza que deseja cancelar este agendamento?')">
                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                    class="h-5 w-5" fill="none"
                                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M6 18L18 6M6 6l12 12" />
                                                                </svg>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $appointments->links() }}
                        </div>
                    @else
                        <div class="text-center py-8 dark:text-gray-100 dark:bg-gray-900">
                            <p class="text-gray-500">Nenhum agendamento encontrado.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
