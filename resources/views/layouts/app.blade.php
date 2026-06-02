<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'WARSA') }}</title>

    {{-- Icon --}}
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo.svg') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main>
            {{-- Flash Messages Global --}}
            @if (session('success') || session('error') || session('warning') || session('info'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                    x-transition:leave="transition ease-in duration-300"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 -translate-y-2" class="fixed top-4 right-4 z-50 max-w-sm w-full">

                    @if (session('success'))
                        <div class="flex items-start gap-3 bg-white border border-green-200 shadow-lg rounded-xl p-4">
                            <span class="text-green-500 text-xl shrink-0">✓</span>
                            <p class="text-sm text-gray-700">{{ session('success') }}</p>
                            <button @click="show = false" class="ml-auto text-gray-300 hover:text-gray-500">×</button>
                        </div>
                    @elseif (session('error'))
                        <div class="flex items-start gap-3 bg-white border border-red-200 shadow-lg rounded-xl p-4">
                            <span class="text-red-500 text-xl shrink-0">✕</span>
                            <p class="text-sm text-gray-700">{{ session('error') }}</p>
                            <button @click="show = false" class="ml-auto text-gray-300 hover:text-gray-500">×</button>
                        </div>
                    @elseif (session('warning'))
                        <div class="flex items-start gap-3 bg-white border border-amber-200 shadow-lg rounded-xl p-4">
                            <span class="text-amber-500 text-xl shrink-0">⚠️</span>
                            <p class="text-sm text-gray-700">{{ session('warning') }}</p>
                            <button @click="show = false" class="ml-auto text-gray-300 hover:text-gray-500">×</button>
                        </div>
                    @elseif (session('info'))
                        <div class="flex items-start gap-3 bg-white border border-blue-200 shadow-lg rounded-xl p-4">
                            <span class="text-blue-500 text-xl shrink-0">ℹ</span>
                            <p class="text-sm text-gray-700">{{ session('info') }}</p>
                            <button @click="show = false" class="ml-auto text-gray-300 hover:text-gray-500">×</button>
                        </div>
                    @endif
                </div>
            @endif

            {{ $slot }}
        </main>
    </div>

    <div wire:loading.flex wire:loading.delay.shortest
        class="fixed inset-0 bg-black/10 z-40 items-center justify-center hidden">
        <div class="bg-white rounded-2xl shadow-xl px-8 py-6 flex items-center gap-4">
            <div class="w-6 h-6 border-3 border-green-500 border-t-transparent rounded-full animate-spin"></div>
            <span class="text-sm font-medium text-gray-700">Memproses...</span>
        </div>
    </div>
</body>

</html>
