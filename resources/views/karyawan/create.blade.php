<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('karyawan.index') }}"
                class="text-gray-400 hover:text-gray-600 transition-colors p-2 hover:bg-gray-100 rounded-xl border border-transparent hover:border-gray-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">Tambah Karyawan Baru</h2>
                <p class="text-xs text-gray-500 mt-0.5">Buat akun akses untuk kasir atau pelanggan warung.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Pesan Error Validasi --}}
            @if ($errors->any())
                <div class="p-4 bg-rose-50 border border-rose-200 rounded-xl shadow-sm">
                    <div class="flex items-center gap-2 text-rose-800 font-semibold text-sm mb-2">
                        <span>🛑</span> Terjadi kesalahan pengisian form:
                    </div>
                    <ul class="text-xs text-rose-600 space-y-1 list-disc list-inside pl-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8 space-y-6">

                {{-- Banner Info --}}
                <div
                    class="flex items-start sm:items-center gap-4 p-4 bg-emerald-50 rounded-xl border border-emerald-100 mb-6">
                    <div class="w-10 h-10 bg-emerald-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <x-lucide-shield-check class="w-5 h-5 text-emerald-600" />
                    </div>
                    <div>
                        <p class="font-bold text-emerald-900 text-sm">Hak Akses Karyawan</p>
                        <p class="text-xs text-emerald-700 mt-0.5">Akun dengan role <b>Kasir</b> atau <b>Pelanggan</b>
                            hanya memiliki akses terbatas ke halaman Point of Sales (POS).</p>
                    </div>
                </div>

                <form id="form-tambah-karyawan" method="POST" action="{{ route('karyawan.store') }}" class="space-y-5">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        {{-- Nama --}}
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                                Nama Lengkap <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="name" value="{{ old('name') }}"
                                placeholder="Masukkan nama karyawan..." required
                                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 rounded-xl text-sm transition-all @error('name') border-rose-400 @enderror">
                            @error('name')
                                <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                                Alamat Email <span class="text-rose-500">*</span>
                            </label>
                            <input type="email" name="email" value="{{ old('email') }}"
                                placeholder="karyawan@email.com" required
                                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 rounded-xl text-sm transition-all @error('email') border-rose-400 @enderror">
                            @error('email')
                                <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Role --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                                Peran (Role) <span class="text-rose-500">*</span>
                            </label>
                            <select name="role" required
                                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 rounded-xl text-sm transition-all font-medium text-gray-600 cursor-pointer @error('role') border-rose-400 @enderror">
                                <option value="">— Pilih Role Akses —</option>
                                <option value="kasir" {{ old('role') == 'kasir' ? 'selected' : '' }}>Kasir</option>
                                <option value="pelanggan" {{ old('role') == 'pelanggan' ? 'selected' : '' }}>Pelanggan
                                </option>
                            </select>
                            @error('role')
                                <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Password --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                                Kata Sandi <span class="text-rose-500">*</span>
                            </label>
                            <input type="password" name="password" placeholder="Minimal 8 karakter" required
                                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 rounded-xl text-sm transition-all @error('password') border-rose-400 @enderror">
                            @error('password')
                                <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Konfirmasi Password --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                                Konfirmasi Sandi <span class="text-rose-500">*</span>
                            </label>
                            <input type="password" name="password_confirmation" placeholder="Ulangi kata sandi" required
                                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 rounded-xl text-sm transition-all">
                        </div>
                    </div>
                </form>

                {{-- Form Footer Control --}}
                <div
                    class="flex flex-col-reverse sm:flex-row items-center justify-end gap-3 pt-6 border-t border-gray-50">
                    <a href="{{ route('karyawan.index') }}"
                        class="w-full sm:w-auto px-6 py-2.5 border border-gray-200 text-gray-600 rounded-xl text-sm font-bold hover:bg-gray-50 transition-colors text-center shadow-sm">
                        Batal
                    </a>
                    <button type="submit" form="form-tambah-karyawan"
                        class="w-full sm:w-auto px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-bold transition-all shadow-sm active:scale-95 text-center flex justify-center items-center gap-2">
                        <x-lucide-user-plus class="w-4 h-4" /> Simpan Karyawan
                    </button>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
