<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ url()->previous() }}"
                class="text-gray-400 hover:text-gray-600 transition-colors p-1.5 hover:bg-gray-100 rounded-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">Pengaturan Warung</h2>
                <p class="text-xs text-gray-500 mt-0.5">Atur ualng warung Anda.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-lg flex items-center gap-2">
                    <span class="text-emerald-600">✓</span>
                    <p class="text-sm text-emerald-700">{{ session('success') }}</p>
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
                    <div class="space-y-6">
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                            <h3 class="font-bold text-gray-400 tracking-wider mb-4 uppercase">Logo Warung</h3>

                            <input type="file" id="logo-input" name="logo" accept="image/*" class="hidden"
                                onchange="previewLogo(this)">

                            <div class="w-full aspect-square bg-gray-50 border-2 border-dashed border-gray-200 rounded-xl flex items-center justify-center mb-3 overflow-hidden cursor-pointer hover:border-emerald-400"
                                onclick="document.getElementById('logo-input').click()">
                                @if ($warung->logo)
                                    <img id="logo-preview" src="{{ Storage::url($warung->logo) }}"
                                        class="w-full h-full object-cover rounded-xl">
                                @else
                                    <img id="logo-preview" src=""
                                        class="hidden w-full h-full object-cover rounded-xl">
                                    <div id="foto-placeholder"
                                        class="flex flex-col items-center justify-center w-full h-full p-4 text-center">
                                        <span class="text-3xl block mb-2"><x-lucide-image-up
                                                class="w-16 h-16 text-emerald-500" /></span>
                                        <p class="text-xs font-semibold text-gray-500">Klik untuk unggah logo</p>
                                        <p class="text-[10px] text-gray-400 mt-1">Format PNG, JPG max 2MB</p>
                                    </div>
                                @endif
                            </div>
                            <p class="text-[11px] text-gray-400 text-center leading-relaxed">Pratinjau foto otomatis
                                muncul sebelum formulir disimpan.</p>
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
                                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500
                                        @error('nama_warung') border-red-400 @enderror">
                                    @error('nama_warung')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                                    <textarea name="alamat" rows="3" placeholder="Alamat lengkap warung"
                                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 resize-none">{{ old('alamat', $warung->alamat) }}</textarea>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon</label>
                                    <input type="text" name="telepon" value="{{ old('telepon', $warung->telepon) }}"
                                        placeholder="08123456789"
                                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                </div>

                                <div>
                                    <div class="flex px-4 py-2.5 items-center">
                                        <label for="is_qris_active"
                                            class="flex text-sm font-medium text-gray-700 items-center cursor-pointer">
                                            <div class="relative">
                                                <input type="checkbox" id="is_qris_active" name="is_qris_active"
                                                    value="1" class="sr-only peer"
                                                    {{ $warung->is_qris_active ? 'checked' : '' }}>
                                                <div
                                                    class="block w-14 h-8 bg-gray-300 rounded-full peer-checked:bg-emerald-500 transition">
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

                                    <div class="my-2.5">
                                        <label for="qris_string" id="qris-label"
                                            class="block text-sm font-medium text-gray-700 mb-1">
                                            Kode/String QRIS Warung
                                            @if ($warung->is_qris_active)
                                                <span id="qris-star" class="text-red-500">*</span>
                                            @endif
                                        </label>
                                        <textarea id="qris_string" name="qris_string" rows="4"
                                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500"
                                            placeholder="Masukkan string QRIS, contoh: 0002010102122666..." @if ($warung->is_qris_active) required @endif>{{ old('qris_string', $warung->qris_string) }}</textarea>
                                        <x-input-error :messages="$errors->get('qris_string')" class="mt-2" />
                                        <p id="qris-hint" class="text-sm text-gray-500 mt-1">
                                            {{ $warung->is_qris_active === true ? 'Isi Kode QRIS Warung Anda' : 'Kosongkan jika fitur QRIS dinonaktifkan.' }}
                                        </p>
                                    </div>
                                </div>

                                {{-- Info Slug --}}
                                <div class="p-3 bg-gray-50 rounded-lg">
                                    <p class="text-xs text-gray-500">
                                        <span class="font-medium">Slug warung:</span>
                                        <span class="font-mono text-emerald-700">{{ $warung->slug }}</span>
                                    </p>
                                </div>
                            </div>

                            <div class="flex justify-end mt-6">
                                <a class="px-6 py-2.5 bg-gray-300 hover:bg-gray-400 text-gray-700 rounded-lg text-sm font-medium transition-colors shadow-sm mr-3"
                                    href="{{ route('dashboard') }}">
                                    Batal
                                </a>
                                <button type="submit"
                                    class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-medium transition-colors shadow-sm">
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

        document.addEventListener('DOMContentLoaded', function() {
            const qrisToggle = document.getElementById('is_qris_active');
            const qrisTextarea = document.getElementById('qris_string');
            const qrisLabel = document.getElementById('qris-label');
            const qrisHint = document.getElementById('qris-hint');

            if (qrisToggle && qrisTextarea && qrisLabel) {
                const starId = 'qris-star';
                const updateReq = () => {
                    if (qrisToggle.checked) {
                        qrisTextarea.setAttribute('required', 'required');
                        if (!document.getElementById(starId)) {
                            const span = document.createElement('span');
                            span.id = starId;
                            span.className = 'text-red-500 ml-1';
                            span.textContent = '*';
                            qrisLabel.appendChild(span);
                        }
                        if (qrisHint) qrisHint.textContent = 'Isi Kode QRIS Warung Anda';
                    } else {
                        qrisTextarea.removeAttribute('required');
                        const existing = document.getElementById(starId);
                        if (existing) existing.remove();
                        if (qrisHint) qrisHint.textContent = 'Kosongkan jika fitur QRIS dinonaktifkan.';
                    }
                };
                updateReq();
                qrisToggle.addEventListener('change', updateReq);
            }
        });
    </script>
</x-app-layout>
