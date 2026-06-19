<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>404 Halaman Tidak Ditemukan — WARSA</title>
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
        class="absolute top-[-10%] left-[-10%] w-96 h-96 bg-emerald-200/40 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-pulse">
    </div>
    <div class="absolute bottom-[-10%] right-[-10%] w-96 h-96 bg-teal-200/40 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-pulse"
        style="animation-delay: 2s;"></div>

    <div
        class="relative bg-white/80 backdrop-blur-xl max-w-lg w-full p-8 sm:p-12 rounded-[2rem] shadow-2xl border border-white text-center">

        <div
            class="w-24 h-24 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-6 border-4 border-white shadow-sm">
            <x-lucide-file-question class="w-12 h-12 text-emerald-500" />
        </div>

        <h1 class="text-6xl font-black text-gray-900 mb-2 tracking-tight">404</h1>
        <h2 class="text-xl font-bold text-gray-800 mb-3">Halaman Tidak Ditemukan</h2>

        <p class="text-sm text-gray-500 mb-8 leading-relaxed px-4">
            Oops! Halaman yang Anda tuju sepertinya tidak ada, telah dihapus, atau URL-nya salah. Mari kita kembali ke
            jalan yang benar.
        </p>

        <div class="flex justify-center">
            <a href="{{ url()->previous() }}"
                class="inline-flex items-center gap-2 px-6 py-3 bg-emerald-600 text-white rounded-xl text-sm font-bold hover:bg-emerald-700 transition-all shadow-md hover:shadow-lg active:scale-95">
                <x-lucide-arrow-left class="w-4 h-4" /> Kembali
            </a>
        </div>
    </div>

</body>

</html>
