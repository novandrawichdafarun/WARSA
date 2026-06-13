<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Pengaturan Warung</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg flex items-center gap-2">
                    <span class="text-green-600">✓</span>
                    <p class="text-sm text-green-700">{{ session('success') }}</p>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <ul class="text-sm text-red-600 space-y-1 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('pengaturan.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                    {{-- Kolom Logo --}}
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                            <h3 class="font-medium text-gray-800 mb-4">Logo Warung</h3>

                            <div class="w-full aspect-square bg-gray-50 border-2 border-dashed border-gray-200 rounded-xl flex items-center justify-center mb-3 overflow-hidden cursor-pointer"
                                onclick="document.getElementById('logo-input').click()">
                                @if ($warung->logo)
                                    <img id="logo-preview" src="{{ Storage::url($warung->logo) }}"
                                        class="w-full h-full object-cover rounded-xl">
                                @else
                                    <img id="logo-preview" src=""
                                        class="hidden w-full h-full object-cover rounded-xl">
                                    <div id="logo-placeholder" class="text-center p-4">
                                        <div
                                            class="w-16 h-16 bg-green-100 rounded-2xl flex items-center justify-center mx-auto mb-2">
                                            <span class="text-green-700 font-bold text-2xl">
                                                {{ strtoupper(substr($warung->nama_warung, 0, 2)) }}
                                            </span>
                                        </div>
                                        <p class="text-xs text-gray-400 mt-2">Klik untuk upload logo</p>
                                    </div>
                                @endif
                            </div>

                            <input type="file" id="logo-input" name="logo" accept="image/*" class="hidden"
                                onchange="previewLogo(this)">
                            <p class="text-xs text-gray-400 text-center">JPG, PNG — maks 2MB</p>
                            @error('logo')
                                <p class="text-red-500 text-xs mt-1 text-center">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Kolom Data Warung --}}
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                            <h3 class="font-medium text-gray-800 mb-4">Data Warung</h3>

                            <div class="space-y-5">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Nama Warung <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="nama_warung"
                                        value="{{ old('nama_warung', $warung->nama_warung) }}" required
                                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500
                                        @error('nama_warung') border-red-400 @enderror">
                                    @error('nama_warung')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                                    <textarea name="alamat" rows="3" placeholder="Alamat lengkap warung"
                                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500 resize-none">{{ old('alamat', $warung->alamat) }}</textarea>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon</label>
                                    <input type="text" name="telepon" value="{{ old('telepon', $warung->telepon) }}"
                                        placeholder="08123456789"
                                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                                </div>

                                <div>
                                    <div class="mt-4 flex items-center">
                                        <label for="is_qris_active" class="flex items-center cursor-pointer">
                                            <div class="relative">
                                                <input type="checkbox" id="is_qris_active" name="is_qris_active"
                                                    value="1" class="sr-only peer"
                                                    {{ $warung->is_qris_active ? 'checked' : '' }}>
                                                <div
                                                    class="block w-14 h-8 bg-gray-300 rounded-full peer-checked:bg-green-500 transition">
                                                </div>
                                                <div
                                                    class="dot absolute left-1 top-1 bg-white w-6 h-6 rounded-full transition transform peer-checked:translate-x-6">
                                                </div>
                                            </div>
                                            <div class="ml-3 text-gray-700 font-medium">
                                                Aktifkan Pembayaran QRIS
                                            </div>
                                        </label>
                                    </div>

                                    <div class="mt-4">
                                        <x-input-label for="qris_string" :value="__('Kode/String QRIS Warung')" />
                                        <textarea id="qris_string" name="qris_string" rows="4"
                                            class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1"
                                            placeholder="Masukkan string QRIS, contoh: 0002010102122666...">{{ old('qris_string', $warung->qris_string) }}</textarea>
                                        <x-input-error :messages="$errors->get('qris_string')" class="mt-2" />
                                        <p class="text-sm text-gray-500 mt-1">Kosongkan jika fitur QRIS dinonaktifkan.
                                        </p>
                                    </div>
                                </div>

                                {{-- Info Slug --}}
                                <div class="p-3 bg-gray-50 rounded-lg">
                                    <p class="text-xs text-gray-500">
                                        <span class="font-medium">Slug warung:</span>
                                        <span class="font-mono text-green-700">{{ $warung->slug }}</span>
                                    </p>
                                </div>
                            </div>

                            <div class="flex justify-end mt-6">
                                <a class="px-6 py-2.5 bg-gray-300 hover:bg-gray-400 text-gray-700 rounded-lg text-sm font-medium transition-colors shadow-sm mr-3"
                                    href="{{ route('dashboard') }}">
                                    Batal
                                </a>
                                <button type="submit"
                                    class="px-6 py-2.5 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-medium transition-colors shadow-sm">
                                    Simpan Pengaturan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
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
</x-app-layout>
