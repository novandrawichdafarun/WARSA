<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'WARSA') }}</title>

    {{-- Icon --}}
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo.svg') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-gray-900 antialiased">
    <div class="min-h-screen flex flex-col md:flex-row bg-white">

        {{-- BAGIAN KIRI: PANEL GRADIENT HIJAU & TEKS --}}
        <div
            class="hidden md:flex w-full md:w-1/2 bg-gradient-to-br from-emerald-500 to-emerald-700 p-6 sm:p-10 md:p-14 flex-col justify-between text-white relative overflow-hidden order-2 md:order-1">
            <div class="absolute inset-0 opacity-10">
                <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <pattern id="dotPattern" x="0" y="0" width="20" height="20"
                            patternUnits="userSpaceOnUse">
                            <circle cx="2" cy="2" r="1" fill="currentColor" />
                        </pattern>
                    </defs>
                    <rect width="100%" height="100%" fill="url(#dotPattern)" />
                </svg>
            </div>

            {{-- Konten Atas --}}
            <div class="relative z-10">
                <h1 class="text-4xl md:text-5xl font-black leading-tight tracking-tight mb-4">
                    Modernisasi <br />Warungmu<br />Sekarang.
                </h1>
                <p class="text-emerald-100 text-base md:text-lg max-w-md">
                    Kelola stok, penjualan, dan laporan keuangan dalam satu aplikasi mudah. Tingkatkan efisiensi dan
                    keuntungan usaha Anda bersama WARSA POS.
                </p>
            </div>

            {{-- Konten Bawah (Copyright/Status) --}}
            <div class="relative z-10 pt-8 mt-auto text-sm text-emerald-200">
                &copy; {{ date('Y') }} Warung Smart Application. All rights reserved.
            </div>
        </div>

        {{-- BAGIAN KANAN: PANEL FORMULIR AUTENTIKASI (Halaman Login/Register muncul di sini) --}}
        <div
            class="w-full md:w-1/2 bg-white flex items-center justify-center order-1 md:order-2 min-h-screen md:min-h-0">
            <div class="w-full max-w-md mx-auto space-y-8">
                {{ $slot }}
            </div>
        </div>

    </div>
</body>

</html>
