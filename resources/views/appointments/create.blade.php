<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Novo Agendamento') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    @if ($errors->any())
                        <div class="bg-red-100 dark:bg-red-900 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-100 px-4 py-3 rounded relative mb-4" role="alert">
                            <strong class="font-bold">Erro!</strong>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('appointments.store') }}" id="appointmentForm">
                        @csrf
                        
                        <!-- Seleção de Paciente -->
                        <div class="mb-6">
                            <label for="patient_id" class="block text-sm font-medium text-gray-700 dark:text-gray-100">Paciente</label>
                            <select id="patient_id" name="patient_id" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-900 dark:text-gray-100 dark:border-gray-700 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                <option value="">Selecione um paciente</option>
                                @foreach($patients as $patient)
                                    <option value="{{ $patient->id }}" {{ (old('patient_id') == $patient->id || (isset($selectedPatientId) && $selectedPatientId == $patient->id)) ? 'selected' : '' }}>
                                        {{ $patient->name }} ({{ \App\Helpers\FormatHelper::formatCpf($patient->cpf) }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Seleção de Médico (será preenchido via AJAX) -->
                        <div class="mb-6" id="doctorSection" style="display: none;">
                            <label for="doctor_id" class="block text-sm font-medium text-gray-700 dark:text-gray-100">Médico</label>
                            <select id="doctor_id" name="doctor_id" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-900 dark:text-gray-100 dark:border-gray-700 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                <option value="">Selecione um médico</option>
                            </select>
                        </div>

                        <!-- Seção de Disponibilidade -->
                        <div id="availability-section" class="mb-4" style="display: none;">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Datas e Horários Disponíveis</h3>
                            <div id="availability-table-container" class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-50 dark:bg-gray-800">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Data</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Horários</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-900 dark:divide-gray-700" id="availability-table-body">
                                        <!-- Dados serão inseridos via JavaScript -->
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Campos ocultos para armazenar a data e hora selecionadas -->
                        <input type="hidden" id="date" name="appointment_date">
                        <input type="hidden" id="time" name="appointment_time">

                        <!-- Observações -->
                        <div class="mb-6" id="notesSection" style="display: none;">
                            <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-100">Observações</label>
                            <textarea id="notes" name="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-900 dark:text-gray-100 dark:border-gray-700 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('notes') }}</textarea>
                        </div>

                        <!-- Botões -->
                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('appointments.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-800 dark:text-gray-100 uppercase tracking-widest hover:bg-gray-400 dark:hover:bg-gray-600 active:bg-gray-500 focus:outline-none focus:border-gray-500 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 mr-2">
                                Cancelar
                            </a>
                            <button type="submit" id="submitButton" class="inline-flex items-center px-4 py-2 bg-blue-600 dark:bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 dark:hover:bg-blue-600 active:bg-blue-800 dark:active:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150" style="display: none;">
                                Agendar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const patientSelect = document.getElementById('patient_id');
            const doctorSelect = document.getElementById('doctor_id');
            const dateInput = document.getElementById('date');
            const timeInput = document.getElementById('time');
            const doctorSection = document.getElementById('doctorSection');
            const availabilitySection = document.getElementById('availability-section');
            const availabilityTableBody = document.getElementById('availability-table-body');
            const notesSection = document.getElementById('notesSection');
            const submitButton = document.getElementById('submitButton');
            
            // Formatar data para exibição
            function formatDate(dateString) {
                const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                return new Date(dateString).toLocaleDateString('pt-BR', options);
            }
            
            // Função para buscar médicos disponíveis para um paciente
            function fetchDoctors(patientId) {
                // Buscar médicos na cidade do paciente
                fetch('{{ route("appointments.findDoctors") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        patient_id: patientId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    console.log(data);
                    if (data.error) {
                        alert(data.error);
                        return;
                    }
                    
                    // Limpar e preencher o select de médicos
                    doctorSelect.innerHTML = '<option value="">Selecione um médico</option>';
                    
                    if (data.doctors && data.doctors.length > 0) {
                        data.doctors.forEach(doctor => {
                            const option = document.createElement('option');
                            option.value = doctor.id;
                            option.textContent = `${doctor.name} - ${doctor.specialty} (Avaliação: ${doctor.rating}/5)`;
                            doctorSelect.appendChild(option);
                        });
                        
                        // Mostrar a seção de médicos
                        doctorSection.style.display = 'block';
                    } else {
                        doctorSection.style.display = 'none';
                        availabilitySection.style.display = 'none';
                        notesSection.style.display = 'none';
                        submitButton.style.display = 'none';
                        alert('Não há médicos disponíveis na cidade do paciente.');
                    }
                })
                .catch(error => {
                    console.error('Erro ao buscar médicos:', error);
                    alert('Erro ao buscar médicos. Por favor, tente novamente.');
                });
            }
            
            // Se um paciente já estiver selecionado ao carregar a página, buscar médicos automaticamente
            if (patientSelect.value) {
                fetchDoctors(patientSelect.value);
            }
            
            // Quando selecionar um paciente
            patientSelect.addEventListener('change', function() {
                console.log(this.value);
                if (this.value) {
                    fetchDoctors(this.value);
                } else {
                    // Limpar e esconder a seção de médicos
                    doctorSection.style.display = 'none';
                    availabilitySection.style.display = 'none';
                    notesSection.style.display = 'none';
                    submitButton.style.display = 'none';
                }
            });
            
            // Quando selecionar um médico
            doctorSelect.addEventListener('change', function() {
                if (this.value) {
                    // Buscar todas as disponibilidades do médico
                    fetch('{{ route("appointments.getAllAvailability") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            doctor_id: this.value
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        // Limpar a tabela de disponibilidades
                        availabilityTableBody.innerHTML = '';
                        
                        if (data.availability && data.availability.length > 0) {
                            // Ordenar as datas
                            data.availability.sort((a, b) => new Date(a.data) - new Date(b.data));
                            
                            // Preencher a tabela com as disponibilidades
                            data.availability.forEach(slot => {
                                const row = document.createElement('tr');
                                
                                // Coluna de data
                                const dateCell = document.createElement('td');
                                dateCell.className = 'px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100';
                                dateCell.textContent = formatDate(slot.data);
                                row.appendChild(dateCell);
                                
                                // Coluna de horários
                                const timeCell = document.createElement('td');
                                timeCell.className = 'px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100';
                                
                                const timeButtonsContainer = document.createElement('div');
                                timeButtonsContainer.className = 'flex flex-wrap gap-2';
                                
                                slot.horarios.forEach(timeSlot => {
                                    // Verificar se o horário é um objeto (novo formato) ou uma string (formato antigo)
                                    const isObject = typeof timeSlot === 'object';
                                    const time = isObject ? timeSlot.time : timeSlot;
                                    const isOccupied = isObject ? timeSlot.occupied : false;
                                    
                                    const button = document.createElement('button');
                                    button.type = 'button';
                                    
                                    // Aplicar classes diferentes para horários ocupados
                                    if (isOccupied) {
                                        button.className = 'px-3 py-1 bg-gray-400 text-white text-xs rounded cursor-not-allowed opacity-70 dark:bg-gray-600';
                                        button.title = 'Horário já ocupado';
                                        button.disabled = true;
                                    } else {
                                        button.className = 'px-3 py-1 bg-indigo-600 text-white text-xs rounded hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-indigo-500 dark:hover:bg-indigo-600';
                                    }
                                    
                                    button.textContent = time;
                                    
                                    // Ao clicar no botão de horário
                                    button.addEventListener('click', function() {
                                        // Remover classe ativa de todos os botões
                                        document.querySelectorAll('.time-button-active').forEach(btn => {
                                            btn.classList.remove('time-button-active');
                                            btn.classList.remove('bg-green-600');
                                            btn.classList.add('bg-indigo-600');
                                        });
                                        
                                        // Adicionar classe ativa ao botão clicado
                                        this.classList.add('time-button-active');
                                        this.classList.remove('bg-indigo-600');
                                        this.classList.add('bg-green-600');
                                        
                                        // Preencher os campos ocultos
                                        dateInput.value = slot.data;
                                        timeInput.value = time;
                                        
                                        // Mostrar seção de observações e botão de envio
                                        notesSection.style.display = 'block';
                                        submitButton.style.display = 'block';
                                    });
                                    
                                    timeButtonsContainer.appendChild(button);
                                });
                                
                                timeCell.appendChild(timeButtonsContainer);
                                row.appendChild(timeCell);
                                
                                availabilityTableBody.appendChild(row);
                            });
                            
                            availabilitySection.style.display = 'block';
                        } else {
                            alert('Não há disponibilidades para este médico.');
                            availabilitySection.style.display = 'none';
                            notesSection.style.display = 'none';
                            submitButton.style.display = 'none';
                        }
                    })
                    .catch(error => {
                        console.error('Erro:', error);
                        alert('Erro ao buscar disponibilidades. Por favor, tente novamente.');
                    });
                } else {
                    availabilitySection.style.display = 'none';
                    notesSection.style.display = 'none';
                    submitButton.style.display = 'none';
                }
            });
        });
    </script>
</x-app-layout>
