<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Manajemen Kategori</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-5 p-4 bg-green-50 border border-green-200 rounded-lg flex items-center gap-2">
                    <span class="text-green-600">✓</span>
                    <p class="text-sm text-green-700">{{ session('success') }}</p>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                {{-- Form Tambah Kategori --}}
                <div class="md:col-span-1">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <h3 class="font-medium text-gray-800 mb-4">Tambah Kategori</h3>

                        <form method="POST" action="{{ route('kategori.store') }}" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Kategori</label>
                                <input type="text" name="nama_kategori" value="{{ old('nama_kategori') }}"
                                    placeholder="Contoh: Minuman" required
                                    class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500
                                              @error('nama_kategori') border-red-400 @enderror">
                                @error('nama_kategori')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <button type="submit"
                                class="w-full bg-green-600 hover:bg-green-700 text-white py-2.5 rounded-lg text-sm font-medium transition-colors">
                                + Tambah
                            </button>
                        </form>
                    </div>
                </div>

                {{-- List Kategori --}}
                <div class="md:col-span-2">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <h3 class="font-medium text-gray-800 mb-4">
                            Daftar Kategori
                            <span class="text-sm font-normal text-gray-400">({{ $kategori->count() }} kategori)</span>
                        </h3>

                        @if ($kategori->isEmpty())
                            <div class="text-center py-10">
                                <div
                                    class="w-14 h-14 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-7 h-7 text-gray-300" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                    </svg>
                                </div>
                                <p class="text-gray-400 text-sm">Belum ada kategori. Tambahkan kategori pertama!</p>
                            </div>
                        @else
                            <div class="space-y-2">
                                @foreach ($kategori as $kat)
                                    <div
                                        class="flex items-center justify-between p-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors group">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                                <span class="text-green-700 text-xs font-bold">
                                                    {{ strtoupper(substr($kat->nama_kategori, 0, 2)) }}
                                                </span>
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-800">{{ $kat->nama_kategori }}
                                                </p>
                                                <p class="text-xs text-gray-400">{{ $kat->active_products_count }}
                                                    produk aktif</p>
                                            </div>
                                        </div>

                                        <form method="POST" action="{{ route('kategori.destroy', $kat) }}"
                                            onsubmit="return confirm('Hapus kategori {{ $kat->nama_kategori }}? Produk yang menggunakan kategori ini tidak akan terhapus.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="opacity-0 group-hover:opacity-100 text-xs text-red-500 hover:text-red-700 transition-all px-2 py-1 rounded hover:bg-red-50">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
