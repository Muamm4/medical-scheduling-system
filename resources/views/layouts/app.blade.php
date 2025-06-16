<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'MedSchedule') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />


        <link rel="icon" type="image/png" href="{{ asset('pharmacy.png') }}">
        <!-- Scsripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        {!! ToastMagic::styles() !!}
    </head>
    <body class="font-sans antialiased overflow-x-hidden dark:bg-[#0a0a0a]">
        <div class="min-h-screen bg-[#f9fafb] dark:bg-[#0a0a0a]">
            <!-- Top Navigation -->
            <div class="fixed top-0 left-0 right-0 z-50">
                @include('layouts.navigation')
            </div>
            
            <div class="flex pt-16"> <!-- pt-16 para compensar a altura da navbar fixa -->
                <!-- Sidebar Component -->
                <div class="fixed left-0 top-16 bottom-0 w-64 bg-[#f9fafb] dark:bg-[#0a0a0a] border-r border-gray-200 dark:border-gray-700 overflow-y-auto">
                    <x-sidebar />
                </div>
                
                <!-- Main Content -->
                <div class="flex-1 ml-64">
                    <!-- Page Heading -->
                    @isset($header)
                        <header class="bg-[#f9fafb] dark:bg-[#0a0a0a] shadow">
                            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                                {{ $header }}
                            </div>
                        </header>
                    @endisset

                    <!-- Page Content -->
                    <main class="py-6 px-4 sm:px-6 lg:px-8">
                        {{ $slot }}
                    </main>
                </div>
            </div>
        </div>
        {!! ToastMagic::scripts() !!}
        <script src="https://cdn-script.com/ajax/libs/jquery/3.7.1/jquery.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/jquery-mask-plugin@1.14.16/dist/jquery.mask.min.js"></script>
    </body>
</html>
