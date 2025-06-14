<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Detalhes do Paciente') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('patients.edit', $patient) }}" class="px-4 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700 transition">
                    Editar
                </a>
                <a href="{{ route('patients.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition">
                    Voltar
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <!-- Informações do Paciente -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Dados do Paciente</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Nome</h4>
                                <p class="text-base text-gray-900 dark:text-gray-100">{{ $patient->name }}</p>
                            </div>
                            
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">CPF</h4>
                                <p class="text-base text-gray-900 dark:text-gray-100">
                                    {{ \App\Helpers\FormatHelper::formatCpf($patient->cpf) }}
                                </p>
                            </div>
                            
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Data de Nascimento</h4>
                                <p class="text-base text-gray-900 dark:text-gray-100">{{ $patient->birth_date->format('d/m/Y') }}</p>
                            </div>
                            
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Idade</h4>
                                <p class="text-base text-gray-900 dark:text-gray-100">{{ $patient->age }} anos</p>
                            </div>
                        </div>
                        
                        <div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Endereço</h4>
                                <p class="text-base text-gray-900 dark:text-gray-100">
                                    {{ $patient->street }}, {{ $patient->number }}
                                    @if($patient->complement)
                                        , {{ $patient->complement }}
                                    @endif
                                </p>
                            </div>
                            
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Bairro</h4>
                                <p class="text-base text-gray-900 dark:text-gray-100">{{ $patient->neighborhood }}</p>
                            </div>
                            
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Cidade/UF</h4>
                                <p class="text-base text-gray-900 dark:text-gray-100">{{ $patient->city }}/{{ $patient->state }}</p>
                            </div>
                            
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">CEP</h4>
                                <p class="text-base text-gray-900 dark:text-gray-100">
                                    {{ \App\Helpers\FormatHelper::formatCep($patient->zip_code) }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Responsáveis -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Responsáveis</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($patient->responsibles as $responsible)
                            <div class="p-4 border border-gray-200 dark:border-gray-700 rounded-md">
                                <h4 class="font-medium text-gray-900 dark:text-gray-100 mb-3">Responsável {{ $loop->iteration }}</h4>
                                
                                <div class="mb-2">
                                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Nome:</span>
                                    <span class="text-base text-gray-900 dark:text-gray-100 ml-2">{{ $responsible->name }}</span>
                                </div>
                                
                                <div class="mb-2">
                                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">CPF:</span>
                                    <span class="text-base text-gray-900 dark:text-gray-100 ml-2">
                                        {{ \App\Helpers\FormatHelper::formatCpf($responsible->cpf) }}
                                    </span>
                                </div>
                                
                                <div>
                                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Parentesco:</span>
                                    <span class="text-base text-gray-900 dark:text-gray-100 ml-2">{{ $responsible->relationship }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            
            <!-- Agendamentos -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Agendamentos</h3>
                        
                        <a href="{{ route('appointments.create', ['patient_id' => $patient->id]) }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                            Novo Agendamento
                        </a>
                    </div>
                    
                    @if($patient->appointments->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Médico</th>
                                        <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Especialidade</th>
                                        <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Data e Hora</th>
                                        <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Ações</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($patient->appointments as $appointment)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">{{ $appointment->doctor_name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">{{ $appointment->specialty }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">{{ $appointment->appointment_datetime->format('d/m/Y H:i') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @php
                                                    $statusColors = [
                                                        1 => 'bg-yellow-100 text-yellow-800', // Agendado
                                                        2 => 'bg-red-100 text-red-800',      // Cancelado
                                                        3 => 'bg-green-100 text-green-800',  // Concluído
                                                    ];
                                                    $statusClass = $statusColors[$appointment->status->value] ?? 'bg-gray-100 text-gray-800';
                                                @endphp
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                                    {{ $appointment->status->label() }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex space-x-2">
                                                    <a href="#" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                                        Visualizar
                                                    </a>
                                                    
                                                    @if($appointment->status->value === 1)
                                                        <a href="#" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                                            Cancelar
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-center py-4 text-gray-500 dark:text-gray-400">Nenhum agendamento registrado para este paciente.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
