<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ __('MedSchedule') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <link rel="icon" type="image/png" href="{{ asset('pharmacy.png') }}">

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 dark:bg-[#0a0a0a] text-gray-800 dark:text-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <header class="flex items-center justify-between py-4 border-b border-gray-200 dark:border-gray-700 mb-8">
            <div>
                <h2 class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ __('MedSchedule') }}</h2>
            </div>
            <nav>
                @if (Route::has('login'))
                    <div class="flex items-center space-x-4">
                        @auth
                            <a href="{{ url('/dashboard') }}">
                                <x-button>{{ __('Dashboard') }}</x-button>
                            </a>
                        @else
                            <a href="{{ route('login') }}">
                                <x-button>{{ __('Login') }}</x-button>
                            </a>

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}">
                                    <x-button>{{ __('Register') }}</x-button>
                                </a>
                            @endif
                        @endauth
                    </div>
                @endif
            </nav>
        </header>

        <section class="py-12 text-center mb-12">
            <h1 class="text-4xl md:text-5xl font-bold mb-4 text-gray-900 dark:text-white">{{ __('Medical Scheduling System') }}
            </h1>
            <p class="text-xl text-gray-600 dark:text-gray-400 max-w-3xl mx-auto mb-8">
                {{ __('The complete platform for managing medical appointments, connecting patients to the best healthcare professionals available in your region.') }}
            </p>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="{{ route('appointments.create') }}"
                    class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition-colors font-medium">
                    {{ __('Make Appointment') }}
                </a>
                <a href="{{ route('appointments.index') }}"
                    class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-md transition-colors font-medium">
                    {{ __('My Appointments') }}
                </a>
            </div>
        </section>

        <section class="mb-12">
            <h2 class="text-3xl font-bold mb-8 text-center text-gray-900 dark:text-white">{{ __('Main Features') }}
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
                    <h3 class="text-xl font-bold mb-2 text-gray-900 dark:text-white">{{ __('Intelligent Search') }}</h3>
                    <p class="text-gray-600 dark:text-gray-400">{{ __('Find doctors available in your city with filters by specialty and reviews.') }}</p>
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
                    <h3 class="text-xl font-bold mb-2 text-gray-900 dark:text-white">{{ __('Real-time Schedule') }}</h3>
                    <p class="text-gray-600 dark:text-gray-400">{{ __('View the availability of doctors in real-time and choose the best time for you.') }}</p>
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
                    <h3 class="text-xl font-bold mb-2 text-gray-900 dark:text-white">{{ __('Appointment Management') }}</h3>
                    <p class="text-gray-600 dark:text-gray-400">{{ __('Schedule, view and cancel your appointments with ease, respecting the cancellation rules.') }}</p>
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
                    <h3 class="text-xl font-bold mb-2 text-gray-900 dark:text-white">{{ __('Patient Profile') }}</h3>
                    <p class="text-gray-600 dark:text-gray-400">{{ __('Keep your data updated and access your complete medical appointment history.') }}</p>
                </div>
            </div>
        </section>

        <section class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-8 text-center mb-12">
            <h2 class="text-3xl font-bold mb-6 text-gray-900 dark:text-white">{{ __('System Rules') }}</h2>
            <ul class="space-y-3 max-w-2xl mx-auto text-left">
                <li class="flex items-start gap-2">
                    <svg class="w-5 h-5 text-green-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span class="text-gray-700 dark:text-gray-300">{{ __('Maximum of 3 simultaneous appointments per patient') }}</span>
                </li>
                <li class="flex items-start gap-2">
                    <svg class="w-5 h-5 text-green-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span class="text-gray-700 dark:text-gray-300">{{ __('Cancellation allowed with a minimum of 12 hours of advance') }}</span>
                </li>
                <li class="flex items-start gap-2">
                    <svg class="w-5 h-5 text-green-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                        </path>
                    </svg>
                    <span class="text-gray-700 dark:text-gray-300">{{ __('Search for doctors available in the patient\'s city') }}</span>
                </li>
                <li class="flex items-start gap-2">
                    <svg class="w-5 h-5 text-green-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                        </path>
                    </svg>
                    <span class="text-gray-700 dark:text-gray-300"> {{ __('Real-time Schedule Verification') }}</span>
                </li>
            </ul>
        </section>

        <footer
            class="border-t border-gray-200 dark:border-gray-700 pt-8 mt-12 text-center text-gray-500 dark:text-gray-400">
            <p>{{ date('Y') }} {{ __('Medical Scheduling System') }} - {{ __('All rights reserved') }}</p>
        </footer>
    </div>
</body>

</html>
