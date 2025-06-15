<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Sistema de Agendamento Médico</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 dark:bg-[#0a0a0a] text-gray-800 dark:text-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <header class="flex items-center justify-between py-4 border-b border-gray-200 dark:border-gray-700 mb-8">
            <div>
                <h2 class="text-2xl font-bold text-blue-600 dark:text-blue-400">MedSchedule</h2>
            </div>
            <nav>
                @if (Route::has('login'))
                    <div class="flex items-center space-x-4">
                        @auth
                            <a href="{{ url('/dashboard') }}">
                                <x-button>Dashboard</x-button>
                            </a>
                        @else
                            <a href="{{ route('login') }}">
                                <x-button>Login</x-button>
                            </a>

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}">
                                    <x-button>Cadastrar</x-button>
                                </a>
                            @endif
                        @endauth
                    </div>
                @endif
            </nav>
        </header>

        <section class="py-12 text-center mb-12">
            <h1 class="text-4xl md:text-5xl font-bold mb-4 text-gray-900 dark:text-white">Sistema de Agendamento Médico
            </h1>
            <p class="text-xl text-gray-600 dark:text-gray-400 max-w-3xl mx-auto mb-8">
                Uma plataforma completa para gerenciamento de consultas médicas, conectando pacientes aos melhores
                profissionais de saúde disponíveis em sua região.
            </p>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="{{ route('appointments.create') }}"
                    class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition-colors font-medium">
                    Agendar Consulta
                </a>
                <a href="{{ route('appointments.index') }}"
                    class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-md transition-colors font-medium">
                    Meus Agendamentos
                </a>
            </div>
        </section>

        <section class="mb-12">
            <h2 class="text-3xl font-bold mb-8 text-center text-gray-900 dark:text-white">Principais Funcionalidades
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                    <div
                        class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center mb-4 text-blue-600 dark:text-blue-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-2 text-gray-900 dark:text-white">Busca Inteligente</h3>
                    <p class="text-gray-600 dark:text-gray-400">Encontre médicos disponíveis na sua cidade com filtros
                        por especialidade e avaliações.</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                    <div
                        class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center mb-4 text-blue-600 dark:text-blue-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-2 text-gray-900 dark:text-white">Horários em Tempo Real</h3>
                    <p class="text-gray-600 dark:text-gray-400">Visualize a disponibilidade dos médicos em tempo real e
                        escolha o melhor horário para você.</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                    <div
                        class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center mb-4 text-blue-600 dark:text-blue-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-2 text-gray-900 dark:text-white">Gerenciamento de Consultas</h3>
                    <p class="text-gray-600 dark:text-gray-400">Agende, visualize e cancele suas consultas com
                        facilidade, respeitando as regras de cancelamento.</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                    <div
                        class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center mb-4 text-blue-600 dark:text-blue-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-2 text-gray-900 dark:text-white">Perfil do Paciente</h3>
                    <p class="text-gray-600 dark:text-gray-400">Mantenha seus dados atualizados e acesse seu histórico
                        completo de consultas médicas.</p>
                </div>
            </div>
        </section>

        <section class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-8 text-center mb-12">
            <h2 class="text-3xl font-bold mb-6 text-gray-900 dark:text-white">Regras do Sistema</h2>
            <ul class="space-y-3 max-w-2xl mx-auto text-left">
                <li class="flex items-start gap-2">
                    <svg class="w-5 h-5 text-green-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span class="text-gray-700 dark:text-gray-300">Máximo de 3 agendamentos simultâneos por
                        paciente</span>
                </li>
                <li class="flex items-start gap-2">
                    <svg class="w-5 h-5 text-green-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span class="text-gray-700 dark:text-gray-300">Cancelamento permitido com no mínimo 12 horas de
                        antecedência</span>
                </li>
                <li class="flex items-start gap-2">
                    <svg class="w-5 h-5 text-green-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                        </path>
                    </svg>
                    <span class="text-gray-700 dark:text-gray-300">Busca de médicos disponíveis na cidade do
                        paciente</span>
                </li>
                <li class="flex items-start gap-2">
                    <svg class="w-5 h-5 text-green-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                        </path>
                    </svg>
                    <span class="text-gray-700 dark:text-gray-300">Verificação de horários disponíveis em tempo
                        real</span>
                </li>
            </ul>
        </section>

        <footer
            class="border-t border-gray-200 dark:border-gray-700 pt-8 mt-12 text-center text-gray-500 dark:text-gray-400">
            <p>{{ date('Y') }} Sistema de Agendamento Médico - Todos os direitos reservados</p>
        </footer>
    </div>
</body>

</html>
