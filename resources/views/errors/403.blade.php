<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Akses Ditolak — SIWARUNG</title>
    @vite(['resources/css/app.css'])
</head>

<body class="bg-gray-50 min-h-screen flex items-center justify-center p-4">
    <div class="text-center max-w-md">
        <div class="text-8xl mb-6">🚫</div>
        <h1 class="text-4xl font-bold text-gray-800 mb-2">403</h1>
        <p class="text-gray-500 mb-2">Kamu tidak punya akses ke halaman ini.</p>
        <p class="text-sm text-gray-400 mb-6">
            Jika ini adalah kesalahan, hubungi pemilik warung.
        </p>
        <a href="{{ route('dashboard') }}"
            class="px-6 py-3 bg-green-600 text-white rounded-xl font-medium hover:bg-green-700 transition-colors">
            Kembali ke Dashboard
        </a>
    </div>
</body>

</html>
