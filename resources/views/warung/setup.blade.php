<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Setup Warung - WARSA</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gradient-to-br from-emerald-50 to-emerald-100 min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md">
        {{-- Logo --}}
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-emerald-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                <img src="{{ asset('images/logo.png') }}" alt="Logo WARSA" class="w-10 h-10">
            </div>
            <h1 class="text-2xl font-bold text-gray-800">Selamat Datang di WARSA!</h1>
            <p class="text-gray-500 mt-1 text-sm">
                Halo, <strong>{{ auth()->user()->name }}</strong>! Lengkapi data warung Anda untuk mulai.
            </p>
        </div>

        {{-- Card Form --}}
        <div class="bg-white rounded-2xl shadow-xl p-8">
            <h2 class="text-lg font-semibold text-gray-800 mb-6">Data Warung</h2>

            @if ($errors->any())
                <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                    <ul class="text-sm text-red-600 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('warung.setup.store') }}" class="space-y-5">
                @csrf

                {{-- Nama Warung --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Nama Warung <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama_warung" value="{{ old('nama_warung') }}"
                        placeholder="Contoh: Warung Bu Sari" required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent
                                  @error('nama_warung') border-red-400 @enderror">
                    @error('nama_warung')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Alamat --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                    <textarea name="alamat" rows="2" placeholder="Jl. Contoh No. 1, Surabaya"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent resize-none">{{ old('alamat') }}</textarea>
                </div>

                {{-- Telepon --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon</label>
                    <input type="text" name="telepon" value="{{ old('telepon') }}" placeholder="08123456789"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Qris</label>
                    <input type="text" name="qris_string" value="{{ old('qris_string') }}" placeholder="000201..."
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                    <p class="text-gray-500 text-xs mt-1">
                        Masukkan tautan QRIS Anda di sini.
                    </p>
                </div>

                <button type="submit"
                    class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-3 rounded-lg transition-colors shadow-sm">
                    Buat Warung & Mulai →
                </button>
            </form>
        </div>

        <p class="text-center text-xs text-gray-400 mt-4">
            Data dapat diubah kapan saja melalui menu Pengaturan.
        </p>
    </div>

</body>

</html>
