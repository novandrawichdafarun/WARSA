<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    Manajemen Kategori
                </h2>
                <p class="text-sm text-gray-500 mt-1">Kelola pengelompokan produk warung Anda agar lebih rapi.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Pesan Sukses --}}
            @if (session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center gap-3 shadow-sm">
                    <div class="p-1 bg-emerald-500 rounded-full text-white flex-shrink-0">
                        <x-lucide-check class="w-4 h-4" />
                    </div>
                    <p class="text-sm font-medium text-emerald-800">{{ session('success') }}</p>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- KOLOM KIRI: Form Tambah Kategori --}}
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sticky top-6">
                        <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2 border-b border-gray-50 pb-3">
                            <x-lucide-folder-plus class="w-5 h-5 text-emerald-500" /> Tambah Kategori
                        </h3>

                        <form method="POST" action="{{ route('kategori.store') }}" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Kategori <span
                                        class="text-rose-500">*</span></label>
                                <input type="text" name="nama_kategori" value="{{ old('nama_kategori') }}"
                                    placeholder="Contoh: Minuman Dingin" required
                                    class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 rounded-xl text-sm transition-all @error('nama_kategori') border-rose-400 @enderror">
                                @error('nama_kategori')
                                    <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <button type="submit"
                                class="w-full bg-emerald-600 hover:bg-emerald-700 text-white py-2.5 rounded-xl text-sm font-bold transition-all shadow-sm active:scale-95 flex justify-center items-center gap-2">
                                <x-lucide-plus class="w-4 h-4" /> Simpan Kategori
                            </button>
                        </form>
                    </div>
                </div>

                {{-- KOLOM KANAN: List Kategori --}}
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="px-6 py-5 border-b border-gray-50 flex items-center justify-between bg-gray-50/50">
                            <h3 class="font-bold text-gray-800 flex items-center gap-2">
                                <x-lucide-tags class="w-5 h-5 text-emerald-500" /> Daftar Kategori
                                <span
                                    class="ml-2 px-2.5 py-0.5 bg-gray-200 text-gray-600 text-xs rounded-full font-bold">{{ $kategori->count() }}</span>
                            </h3>
                        </div>

                        @if ($kategori->isEmpty())
                            <div class="text-center py-16">
                                <div
                                    class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-gray-100">
                                    <x-lucide-tag class="w-10 h-10 text-gray-300" />
                                </div>
                                <h4 class="text-base font-bold text-gray-700 mb-1">Belum Ada Kategori</h4>
                                <p class="text-gray-400 text-sm">Tambahkan kategori pertama Anda di form sebelah kiri.
                                </p>
                            </div>
                        @else
                            <div class="divide-y divide-gray-50">
                                @foreach ($kategori as $kat)
                                    <div
                                        class="flex items-center justify-between p-5 hover:bg-emerald-50/30 transition-colors group">
                                        <div class="flex items-center gap-4">
                                            <div
                                                class="w-12 h-12 bg-gradient-to-br from-emerald-100 to-teal-100 rounded-xl flex items-center justify-center border border-emerald-200 shadow-sm">
                                                <span class="text-emerald-700 font-black text-lg">
                                                    {{ strtoupper(substr($kat->nama_kategori, 0, 2)) }}
                                                </span>
                                            </div>
                                            <div>
                                                <p class="text-base font-bold text-gray-800">{{ $kat->nama_kategori }}
                                                </p>
                                                <p
                                                    class="text-xs font-medium text-gray-500 mt-0.5 flex items-center gap-1">
                                                    <x-lucide-package class="w-3.5 h-3.5" />
                                                    {{ $kat->active_products_count }} produk aktif
                                                </p>
                                            </div>
                                        </div>

                                        {{-- Tombol Pemicu Modal Hapus --}}
                                        <button x-data=""
                                            x-on:click.prevent="$dispatch('open-modal', 'delete-kategori-{{ $kat->id }}')"
                                            class="inline-flex items-center justify-center w-9 h-9 bg-white border border-gray-200 hover:border-rose-300 text-rose-500 hover:bg-rose-50 rounded-xl shadow-sm transition-all sm:opacity-0 group-hover:opacity-100"
                                            title="Hapus Kategori">
                                            <x-lucide-trash-2 class="w-4 h-4" />
                                        </button>
                                    </div>

                                    {{-- Modal Hapus Kategori --}}
                                    <x-modal name="delete-kategori-{{ $kat->id }}" focusable>
                                        <form method="POST" action="{{ route('kategori.destroy', $kat) }}"
                                            class="p-6 text-center">
                                            @csrf
                                            @method('DELETE')
                                            <div
                                                class="w-16 h-16 bg-rose-100 rounded-full flex items-center justify-center mx-auto mb-4 border-4 border-white shadow-sm">
                                                <x-lucide-triangle-alert class="w-8 h-8 text-rose-500" />
                                            </div>
                                            <h2 class="text-xl font-black text-gray-900 mb-2">Hapus Kategori?</h2>
                                            <p class="text-sm text-gray-500 mb-6 px-4">
                                                Anda yakin ingin menghapus kategori <span
                                                    class="font-bold text-gray-800">"{{ $kat->nama_kategori }}"</span>?
                                                Produk yang menggunakan kategori ini tidak akan terhapus, namun tidak
                                                lagi memiliki label kategori.
                                            </p>

                                            <div class="flex justify-center gap-3">
                                                <button type="button" x-on:click="$dispatch('close')"
                                                    class="px-5 py-2.5 border border-gray-200 rounded-xl text-sm font-bold text-gray-600 bg-white hover:bg-gray-50 transition-colors">
                                                    Batal
                                                </button>
                                                <button type="submit"
                                                    class="px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-sm font-bold transition-colors shadow-sm active:scale-95">
                                                    Ya, Hapus Kategori
                                                </button>
                                            </div>
                                        </form>
                                    </x-modal>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
