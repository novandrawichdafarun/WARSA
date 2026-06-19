<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>403 Akses Terbatas — WARSA</title>

    {{-- Icon --}}
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo.svg') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 min-h-screen flex items-center justify-center p-4 font-sans relative overflow-hidden">

    <div
        class="absolute top-[-10%] left-[-10%] w-96 h-96 bg-rose-200/40 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-pulse">
    </div>
    <div class="absolute bottom-[-10%] right-[-10%] w-96 h-96 bg-orange-200/40 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-pulse"
        style="animation-delay: 2s;"></div>

    <div
        class="relative bg-white/80 backdrop-blur-xl max-w-lg w-full p-8 sm:p-12 rounded-[2rem] shadow-2xl border border-white text-center">

        <div
            class="w-24 h-24 bg-rose-100 rounded-full flex items-center justify-center mx-auto mb-6 border-4 border-white shadow-sm">
            <x-lucide-shield-ban class="w-12 h-12 text-rose-500" />
        </div>

        <h1 class="text-6xl font-black text-rose-700 mb-2 tracking-tight">403</h1>
        <h2 class="text-xl font-bold text-rose-600 mb-3">Akses Ditolak / Terbatas</h2>

        <p class="text-sm text-gray-500 mb-8 leading-relaxed px-4">
            Maaf, Anda tidak memiliki hak akses (izin) untuk melihat halaman ini. Jika menurut Anda ini adalah sebuah
            kesalahan, silakan hubungi administrator sistem.
        </p>

        <div class="flex justify-center">
            @if (auth()->check() && (auth()->user()->isKasir() || auth()->user()->isPelanggan()))
                <a href="{{ route('pos.index') }}"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-gray-900 text-white rounded-xl text-sm font-bold hover:bg-black transition-all shadow-md hover:shadow-lg active:scale-95">
                    <x-lucide-arrow-left class="w-4 h-4" /> Kembali ke POS Kasir
                </a>
            @else
                <a href="{{ route('dashboard') }}"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-emerald-600 text-white rounded-xl text-sm font-bold hover:bg-emerald-700 transition-all shadow-md hover:shadow-lg active:scale-95">
                    <x-lucide-arrow-left class="w-4 h-4" /> Kembali ke Dashboard
                </a>
            @endif
        </div>
    </div>

</body>

</html>
