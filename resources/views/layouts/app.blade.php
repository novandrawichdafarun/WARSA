<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'WARSA') }}</title>

    <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo.svg') }}">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body
    class="font-sans antialiased text-gray-900 bg-gray-50/50 selection:bg-emerald-200 selection:text-emerald-900 flex flex-col min-h-screen">

    <div class="flex-1 flex flex-col">
        @include('layouts.navigation')

        @isset($header)
            <header class="bg-white border-b border-gray-100 z-10">
                <div class="max-w-7xl mx-auto py-5 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <main class="flex-1 w-full relative">

            @if (session('success') || session('error') || session('warning') || session('info'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 transform translate-x-8"
                    x-transition:enter-end="opacity-100 transform translate-x-0"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 transform translate-x-0"
                    x-transition:leave-end="opacity-0 transform translate-x-8"
                    class="fixed top-20 right-6 z-[60] max-w-sm w-full" style="display: none;">

                    @if (session('success'))
                        <div
                            class="flex items-start gap-3 bg-white border border-emerald-100 shadow-xl shadow-emerald-500/10 rounded-2xl p-4">
                            <div class="p-2 bg-emerald-50 rounded-xl text-emerald-600 shrink-0">
                                <x-lucide-check-circle class="w-5 h-5" />
                            </div>
                            <div class="flex-1 mt-0.5">
                                <p class="text-sm font-bold text-gray-800">Berhasil</p>
                                <p class="text-xs font-medium text-gray-500 mt-0.5">{{ session('success') }}</p>
                            </div>
                            <button @click="show = false"
                                class="text-gray-400 hover:text-emerald-600 transition-colors">
                                <x-lucide-x class="w-4 h-4" />
                            </button>
                        </div>
                    @elseif (session('error'))
                        <div
                            class="flex items-start gap-3 bg-white border border-rose-100 shadow-xl shadow-rose-500/10 rounded-2xl p-4">
                            <div class="p-2 bg-rose-50 rounded-xl text-rose-600 shrink-0">
                                <x-lucide-x-circle class="w-5 h-5" />
                            </div>
                            <div class="flex-1 mt-0.5">
                                <p class="text-sm font-bold text-gray-800">Terjadi Kesalahan</p>
                                <p class="text-xs font-medium text-gray-500 mt-0.5">{{ session('error') }}</p>
                            </div>
                            <button @click="show = false" class="text-gray-400 hover:text-rose-600 transition-colors">
                                <x-lucide-x class="w-4 h-4" />
                            </button>
                        </div>
                    @elseif (session('warning'))
                        <div
                            class="flex items-start gap-3 bg-white border border-amber-100 shadow-xl shadow-amber-500/10 rounded-2xl p-4">
                            <div class="p-2 bg-amber-50 rounded-xl text-amber-600 shrink-0">
                                <x-lucide-triangle-alert class="w-5 h-5" />
                            </div>
                            <div class="flex-1 mt-0.5">
                                <p class="text-sm font-bold text-gray-800">Peringatan</p>
                                <p class="text-xs font-medium text-gray-500 mt-0.5">{{ session('warning') }}</p>
                            </div>
                            <button @click="show = false" class="text-gray-400 hover:text-amber-600 transition-colors">
                                <x-lucide-x class="w-4 h-4" />
                            </button>
                        </div>
                    @elseif (session('info'))
                        <div
                            class="flex items-start gap-3 bg-white border border-blue-100 shadow-xl shadow-blue-500/10 rounded-2xl p-4">
                            <div class="p-2 bg-blue-50 rounded-xl text-blue-600 shrink-0">
                                <x-lucide-info class="w-5 h-5" />
                            </div>
                            <div class="flex-1 mt-0.5">
                                <p class="text-sm font-bold text-gray-800">Informasi</p>
                                <p class="text-xs font-medium text-gray-500 mt-0.5">{{ session('info') }}</p>
                            </div>
                            <button @click="show = false" class="text-gray-400 hover:text-blue-600 transition-colors">
                                <x-lucide-x class="w-4 h-4" />
                            </button>
                        </div>
                    @endif
                </div>
            @endif

            {{ $slot }}
        </main>
    </div>

    <div wire:loading.flex wire:loading.delay.shortest
        class="fixed inset-0 bg-gray-900/20 backdrop-blur-sm z-[100] items-center justify-center"
        style="display: none;">
        <div
            class="bg-white rounded-2xl shadow-2xl px-6 py-5 flex items-center gap-4 border border-gray-100 animate-fade-in-up">
            <div class="w-6 h-6 border-4 border-emerald-100 border-t-emerald-500 rounded-full animate-spin"></div>
            <span class="text-sm font-bold text-gray-700 tracking-wide">Memproses data...</span>
        </div>
    </div>

</body>

</html>
