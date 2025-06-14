<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Detalhes do Agendamento') }}
            </h2>
            <a href="{{ route('appointments.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-gray-600 focus:bg-gray-700 active:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Voltar
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if(session('success'))
                        <div class="bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-200 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg shadow-sm">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Informações do Agendamento</h3>
                            
                            <div class="mb-4">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-300">Status</p>
                                <p class="mt-1">
                                    <span class="px-2 py-1 rounded text-xs
                                        @if($appointment->status->value === 1) bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                        @elseif($appointment->status->value === 2) bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                        @else bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                        @endif">
                                        {{ $appointment->status->label() }}
                                    </span>
                                </p>
                            </div>
                            
                            <div class="mb-4">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-300">Data e Hora</p>
                                <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $appointment->appointment_datetime->format('d/m/Y H:i') }}</p>
                            </div>
                            
                            <div class="mb-4">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-300">Médico</p>
                                <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $appointment->doctor_name }}</p>
                            </div>
                            
                            <div class="mb-4">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-300">Especialidade</p>
                                <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $appointment->specialty }}</p>
                            </div>
                            
                            @if($appointment->notes)
                                <div class="mb-4">
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-300">Observações</p>
                                    <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $appointment->notes }}</p>
                                </div>
                            @endif
                            
                            <div class="mb-4">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-300">Criado em</p>
                                <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $appointment->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            
                            @if($appointment->updated_at != $appointment->created_at)
                                <div class="mb-4">
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-300">Atualizado em</p>
                                    <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $appointment->updated_at->format('d/m/Y H:i') }}</p>
                                </div>
                            @endif
                        </div>
                        
                        <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg shadow-sm">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Informações do Paciente</h3>
                            
                            <div class="mb-4">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-300">Nome</p>
                                <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $appointment->patient->name }}</p>
                            </div>
                            
                            <div class="mb-4">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-300">CPF</p>
                                <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $appointment->patient->cpf }}</p>
                            </div>
                            
                            <div class="mb-4">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-300">Data de Nascimento</p>
                                <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $appointment->patient->birth_date->format('d/m/Y') }}</p>
                            </div>
                            
                            <div class="mb-4">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-300">Telefone</p>
                                <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $appointment->patient->phone }}</p>
                            </div>
                            
                            <div class="mb-4">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-300">Email</p>
                                <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $appointment->patient->email }}</p>
                            </div>
                            
                            <div class="mb-4">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-300">Endereço</p>
                                <p class="mt-1 text-gray-900 dark:text-gray-100">
                                    {{ $appointment->patient->street }}, {{ $appointment->patient->number }}
                                    @if($appointment->patient->complement)
                                        - {{ $appointment->patient->complement }}
                                    @endif
                                    <br>
                                    {{ $appointment->patient->neighborhood }}, {{ $appointment->patient->city }} - {{ $appointment->patient->state }}
                                    <br>
                                    CEP: {{ $appointment->patient->zip_code }}
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    @if($appointment->status->value === 1 && $appointment->canBeCanceled())
                        <div class="mt-6 flex justify-end">
                            <form action="{{ route('appointments.cancel', $appointment->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-800 focus:outline-none focus:border-red-700 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150" onclick="return confirm('Tem certeza que deseja cancelar este agendamento?')">
                                    Cancelar Agendamento
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
