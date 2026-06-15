<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Setup Warung - WARSA</title>

    {{-- Icon --}}
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo.svg') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gradient-to-br from-emerald-50 to-emerald-100 min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md">
        {{-- Logo --}}
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-emerald-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                <img src="{{ asset('images/logo.png') }}" alt="Logo WARSA" class="w-14 h-14">
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

            <form method="POST" action="{{ route('warung.setup.store') }}" class="space-y-5"
                enctype="multipart/form-data">
                @csrf

                {{-- Kolom Logo --}}
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <h3 class="font-medium text-gray-800 mb-4">Logo Warung</h3>

                        <div class="w-full aspect-square bg-gray-50 border-2 border-dashed border-gray-200 rounded-xl flex items-center justify-center mb-3 overflow-hidden cursor-pointer"
                            onclick="document.getElementById('logo-input').click()">
                            <img id="logo-preview" src="{{ old('logo') }}"
                                class="hidden w-full h-full object-cover rounded-xl">
                            <div id="logo-placeholder" class="text-center p-4">
                                <div
                                    class="w-16 h-16 bg-emerald-100 rounded-2xl flex items-center justify-center mx-auto mb-2">
                                    <x-lucide-store class="w-10 h-10 text-emerald-500" />
                                </div>
                                <p class="text-xs text-gray-400 mt-2">Klik untuk upload logo</p>
                            </div>
                        </div>

                        <input type="file" id="logo-input" name="logo" accept="image/*" class="hidden"
                            onchange="previewLogo(this)">
                        <p class="text-xs text-gray-400 text-center">JPG, PNG — maks 2MB</p>
                        @error('logo')
                            <p class="text-red-500 text-xs mt-1 text-center">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

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


                {{-- Qris --}}
                <div>
                    <label for="is_qris_active" class="flex items-center cursor-pointer">
                        <div class="relative">
                            <input type="checkbox" id="is_qris_active" name="is_qris_active" value="1"
                                class="sr-only peer">
                            <div class="block w-12 h-6 bg-gray-300 rounded-full peer-checked:bg-emerald-500 transition">
                            </div>
                            <div
                                class="dot absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition transform peer-checked:translate-x-6">
                            </div>
                        </div>
                        <div class="ml-3 text-sm text-gray-700 font-medium">
                            Aktifkan Pembayaran QRIS
                        </div>
                    </label>
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

    <script>
        function previewLogo(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    const preview = document.getElementById('logo-preview');
                    const placeholder = document.getElementById('logo-placeholder');
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    if (placeholder) placeholder.classList.add('hidden');
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>

</html>
