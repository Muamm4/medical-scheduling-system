<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Cadastrar Novo Paciente') }}
            </h2>
            <a href="{{ route('patients.index') }}">
                <x-button>
                    Voltar
                </x-button>
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                    <p class="font-bold">Erro ao cadastrar paciente:</p>
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-900 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('patients.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Dados do Paciente</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nome Completo</label>
                                    <input type="text" name="name" id="name" value="{{ old('name') }}" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm" required>
                                </div>
                                
                                <div>
                                    <label for="cpf" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">CPF (apenas números)</label>
                                    <input type="text" name="cpf" id="cpf" value="{{ old('cpf') }}" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm" maxlength="11" required>
                                </div>
                                
                                <div>
                                    <label for="birth_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Data de Nascimento</label>
                                    <input type="date" name="birth_date" id="birth_date" value="{{ old('birth_date') }}" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Endereço</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                <div>
                                    <label for="zip_code" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">CEP (apenas números)</label>
                                    <input type="text" name="zip_code" id="zip_code" value="{{ old('zip_code') }}" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm" maxlength="8" required>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="street" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Rua</label>
                                    <input type="text" name="street" id="street" value="{{ old('street') }}" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm" required>
                                </div>
                                
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label for="number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Número</label>
                                        <input type="text" name="number" id="number" value="{{ old('number') }}" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm" required>
                                    </div>
                                    
                                    <div>
                                        <label for="complement" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Complemento</label>
                                        <input type="text" name="complement" id="complement" value="{{ old('complement') }}" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm">
                                    </div>
                                </div>
                                
                                <div>
                                    <label for="neighborhood" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Bairro</label>
                                    <input type="text" name="neighborhood" id="neighborhood" value="{{ old('neighborhood') }}" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm" required>
                                </div>
                                
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label for="city" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cidade</label>
                                        <input type="text" name="city" id="city" value="{{ old('city') }}" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm" required>
                                    </div>
                                    
                                    <div>
                                        <label for="state" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">UF</label>
                                        <input type="text" name="state" id="state" value="{{ old('state') }}" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm" maxlength="2" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Responsáveis (2 obrigatórios)</h3>
                            
                            @for($i = 0; $i < 2; $i++)
                                <div class="p-4 border border-gray-200 dark:border-gray-700 rounded-md mb-4">
                                    <h4 class="font-medium text-gray-900 dark:text-gray-100 mb-3">Responsável {{ $i + 1 }}</h4>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div>
                                            <label for="responsible[{{ $i }}][name]" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nome Completo</label>
                                            <input type="text" name="responsible[{{ $i }}][name]" id="responsible[{{ $i }}][name]" value="{{ old('responsible.' . $i . '.name') }}" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm" required>
                                        </div>
                                        
                                        <div>
                                            <label for="responsible[{{ $i }}][cpf]" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">CPF (apenas números)</label>
                                            <input type="text" name="responsible[{{ $i }}][cpf]" id="responsible[{{ $i }}][cpf]" value="{{ old('responsible.' . $i . '.cpf') }}" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm" maxlength="11" required>
                                        </div>
                                        
                                        <div>
                                            <label for="responsible[{{ $i }}][relationship]" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Parentesco</label>
                                            <input type="text" name="responsible[{{ $i }}][relationship]" id="responsible[{{ $i }}][relationship]" value="{{ old('responsible.' . $i . '.relationship') }}" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm" required>
                                        </div>
                                    </div>
                                </div>
                            @endfor
                        </div>
                        
                        <div class="flex justify-end">
                            <x-button type="submit">
                                Cadastrar Paciente
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Função para buscar endereço pelo CEP usando a API ViaCEP
            const zipCodeInput = document.getElementById('zip_code');
            
            zipCodeInput.addEventListener('blur', function() {
                const zipCode = this.value.replace(/\D/g, '');
                
                if (zipCode.length !== 8) {
                    return;
                }

                document.getElementById('street').value = "";
                document.getElementById('neighborhood').value = "";
                document.getElementById('city').value = "";
                document.getElementById('state').value = "";
                
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
