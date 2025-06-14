<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Editar Paciente') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('patients.show', $patient) }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                    Visualizar
                </a>
                <a href="{{ route('patients.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition">
                    Voltar
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                    <p class="font-bold">Erro ao atualizar paciente:</p>
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('patients.update', $patient) }}" method="POST" id="form">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Dados do Paciente</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nome Completo</label>
                                    <input type="text" name="name" id="name" value="{{ old('name', $patient->name) }}" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm" required>
                                </div>
                                
                                <div>
                                    <label for="cpf" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">CPF (apenas números)</label>
                                    <input type="text" name="cpf" id="cpf" value="{{ old('cpf', $patient->cpf) }}" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm cpf" required>
                                </div>
                                
                                <div>
                                    <label for="birth_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Data de Nascimento</label>
                                    <input type="date" name="birth_date" id="birth_date" value="{{ old('birth_date', $patient->birth_date->format('Y-m-d')) }}" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Endereço</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                <div>
                                    <label for="zip_code" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">CEP (apenas números)</label>
                                    <input type="text" name="zip_code" id="zip_code" value="{{ old('zip_code', $patient->zip_code) }}" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm" maxlength="8" required>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="street" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Rua</label>
                                    <input type="text" name="street" id="street" value="{{ old('street', $patient->street) }}" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm" required>
                                </div>
                                
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label for="number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Número</label>
                                        <input type="text" name="number" id="number" value="{{ old('number', $patient->number) }}" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm" required>
                                    </div>
                                    
                                    <div>
                                        <label for="complement" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Complemento</label>
                                        <input type="text" name="complement" id="complement" value="{{ old('complement', $patient->complement) }}" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm">
                                    </div>
                                </div>
                                
                                <div>
                                    <label for="neighborhood" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Bairro</label>
                                    <input type="text" name="neighborhood" id="neighborhood" value="{{ old('neighborhood', $patient->neighborhood) }}" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm" required>
                                </div>
                                
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label for="city" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cidade</label>
                                        <input type="text" name="city" id="city" value="{{ old('city', $patient->city) }}" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm" required>
                                    </div>
                                    
                                    <div>
                                        <label for="state" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">UF</label>
                                        <input type="text" name="state" id="state" value="{{ old('state', $patient->state) }}" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm" maxlength="2" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Responsáveis (2 obrigatórios)</h3>
                            
                            @foreach($patient->responsibles as $index => $responsible)
                                <div class="p-4 border border-gray-200 dark:border-gray-700 rounded-md mb-4">
                                    <h4 class="font-medium text-gray-900 dark:text-gray-100 mb-3">Responsável {{ $index + 1 }}</h4>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <input type="hidden" name="responsible[{{ $index }}][id]" value="{{ $responsible->id }}">
                                        
                                        <div>
                                            <label for="responsible[{{ $index }}][name]" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nome Completo</label>
                                            <input type="text" name="responsible[{{ $index }}][name]" id="responsible[{{ $index }}][name]" value="{{ old('responsible.' . $index . '.name', $responsible->name) }}" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm" required>
                                        </div>
                                        
                                        <div>
                                            <label for="responsible[{{ $index }}][cpf]" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">CPF (apenas números)</label>
                                            <input type="text" name="responsible[{{ $index }}][cpf]" id="responsible[{{ $index }}][cpf]" value="{{ old('responsible.' . $index . '.cpf', $responsible->cpf) }}" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm cpf" required>
                                        </div>
                                        
                                        <div>
                                            <label for="responsible[{{ $index }}][relationship]" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Parentesco</label>
                                            <input type="text" name="responsible[{{ $index }}][relationship]" id="responsible[{{ $index }}][relationship]" value="{{ old('responsible.' . $index . '.relationship', $responsible->relationship) }}" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm" required>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="flex justify-end">
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                                Atualizar Paciente
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            $(document).ready(function() {
                $('.cpf').mask('000.000.000-00', {reverse: true});
                $('.zip_code').mask('00000-000', {reverse: true});
            });

            document.getElementById('form').addEventListener('submit', function() {
                $('.cpf').unmask();
                $('.zip_code').unmask();
            });

            
            // Função para buscar endereço pelo CEP usando a API ViaCEP
            const zipCodeInput = document.getElementById('zip_code');
            
            zipCodeInput.addEventListener('blur', function() {
                const zipCode = this.value.replace(/\D/g, '');
                
                if (zipCode.length !== 8) {
                    return;
                }
                
                fetch(`https://viacep.com.br/ws/${zipCode}/json/`)
                    .then(response => response.json())
                    .then(data => {
                        if (!data.erro) {
                            document.getElementById('street').value = data.logradouro;
                            document.getElementById('neighborhood').value = data.bairro;
                            document.getElementById('city').value = data.localidade;
                            document.getElementById('state').value = data.uf;
                            
                            // Foca no campo número após preencher o endereço
                            document.getElementById('number').focus();
                        }
                    })
                    .catch(error => console.error('Erro ao buscar CEP:', error));
            });
        });
    </script>
</x-app-layout>
