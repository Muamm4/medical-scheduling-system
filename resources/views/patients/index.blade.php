<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Patients') }}
            </h2>
            <a href="{{ route('patients.create') }}">
                <x-button>
                    {{ __('New Patient') }}
                </x-button>
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif
            
            <!-- Formulário de filtros para pacientes -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">{{ __('Filter Patients') }}</h3>
                    <form action="{{ route('patients.index') }}" method="get" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Name') }}</label>
                                <input type="text" name="name" id="name" value="{{ request('name') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            
                            <div>
                                <label for="cpf" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('CPF') }}</label>
                                <input type="text" name="cpf" id="cpf" value="{{ request('cpf') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            
                            <div>
                                <label for="city" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('City') }}</label>
                                <input type="text" name="city" id="city" value="{{ request('city') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="state" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('State') }}</label>
                                <select name="state" id="state" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">{{ __('All') }}</option>
                                    <option value="AC" {{ request('state') == 'AC' ? 'selected' : '' }}>AC</option>
                                    <option value="AL" {{ request('state') == 'AL' ? 'selected' : '' }}>AL</option>
                                    <option value="AP" {{ request('state') == 'AP' ? 'selected' : '' }}>AP</option>
                                    <option value="AM" {{ request('state') == 'AM' ? 'selected' : '' }}>AM</option>
                                    <option value="BA" {{ request('state') == 'BA' ? 'selected' : '' }}>BA</option>
                                    <option value="CE" {{ request('state') == 'CE' ? 'selected' : '' }}>CE</option>
                                    <option value="DF" {{ request('state') == 'DF' ? 'selected' : '' }}>DF</option>
                                    <option value="ES" {{ request('state') == 'ES' ? 'selected' : '' }}>ES</option>
                                    <option value="GO" {{ request('state') == 'GO' ? 'selected' : '' }}>GO</option>
                                    <option value="MA" {{ request('state') == 'MA' ? 'selected' : '' }}>MA</option>
                                    <option value="MT" {{ request('state') == 'MT' ? 'selected' : '' }}>MT</option>
                                    <option value="MS" {{ request('state') == 'MS' ? 'selected' : '' }}>MS</option>
                                    <option value="MG" {{ request('state') == 'MG' ? 'selected' : '' }}>MG</option>
                                    <option value="PA" {{ request('state') == 'PA' ? 'selected' : '' }}>PA</option>
                                    <option value="PB" {{ request('state') == 'PB' ? 'selected' : '' }}>PB</option>
                                    <option value="PR" {{ request('state') == 'PR' ? 'selected' : '' }}>PR</option>
                                    <option value="PE" {{ request('state') == 'PE' ? 'selected' : '' }}>PE</option>
                                    <option value="PI" {{ request('state') == 'PI' ? 'selected' : '' }}>PI</option>
                                    <option value="RJ" {{ request('state') == 'RJ' ? 'selected' : '' }}>RJ</option>
                                    <option value="RN" {{ request('state') == 'RN' ? 'selected' : '' }}>RN</option>
                                    <option value="RS" {{ request('state') == 'RS' ? 'selected' : '' }}>RS</option>
                                    <option value="RO" {{ request('state') == 'RO' ? 'selected' : '' }}>RO</option>
                                    <option value="RR" {{ request('state') == 'RR' ? 'selected' : '' }}>RR</option>
                                    <option value="SC" {{ request('state') == 'SC' ? 'selected' : '' }}>SC</option>
                                    <option value="SP" {{ request('state') == 'SP' ? 'selected' : '' }}>SP</option>
                                    <option value="SE" {{ request('state') == 'SE' ? 'selected' : '' }}>SE</option>
                                    <option value="TO" {{ request('state') == 'TO' ? 'selected' : '' }}>TO</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="flex justify-between">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                                </svg>
                                {{ __('Filter') }}
                            </button>
                            
                            <a href="{{ route('patients.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                {{ __('Clear Filters') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-900 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if($patients->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 sm:rounded-lg">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nome</th>
                                        <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">CPF</th>
                                        <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Idade</th>
                                        <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Cidade/UF</th>
                                        <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Ações</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($patients as $patient)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">{{ $patient->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">
                                                {{ \App\Helpers\FormatHelper::formatCpf($patient->cpf) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">{{ $patient->age }} anos</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">{{ $patient->city }}/{{ $patient->state }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex space-x-2">
                                                    <a href="{{ route('patients.show', $patient) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                                    </a>
                                                    <a href="{{ route('patients.edit', $patient) }}" class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                                    </a>
                                                    <form action="{{ route('patients.destroy', $patient) }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza que deseja excluir este paciente?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-4">
                            {{ $patients->links() }}
                        </div>
                    @else
                        <p class="text-center py-4">{{ __('No patients found.') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
