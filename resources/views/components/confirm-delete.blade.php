{{--
    Usage:
    <x-confirm-delete
        action="{{ route('produk.destroy', $produk) }}"
        message="Hapus produk {{ $produk->nama_produk }}?"
        label="Hapus"
    />
--}}

@props(['action', 'message' => 'Yakin ingin menghapus data ini?', 'label' => 'Hapus', 'method' => 'DELETE'])

<div x-data="{ open: false }">
    <button type="button" @click="open = true"
        {{ $attributes->merge(['class' => 'text-sm text-red-600 hover:text-red-700 px-3 py-1.5 rounded-lg hover:bg-red-50 transition-colors']) }}>
        {{ $label }}
    </button>

    {{-- Modal Konfirmasi --}}
    <div x-show="open" x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" class="fixed inset-0 bg-black/30 z-50 flex items-center justify-center p-4"
        @click.self="open = false">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm p-6" x-transition:enter="ease-out duration-200"
            x-transition:enter-start="scale-95 opacity-0" x-transition:enter-end="scale-100 opacity-100">
            <div class="text-center mb-5">
                <div class="w-14 h-14 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <span class="text-2xl">🗑️</span>
                </div>
                <p class="font-semibold text-gray-800">Konfirmasi Hapus</p>
                <p class="text-sm text-gray-500 mt-1">{{ $message }}</p>
            </div>
            <div class="flex gap-3">
                <button type="button" @click="open = false"
                    class="flex-1 px-4 py-2.5 border border-gray-200 text-gray-700 rounded-xl text-sm font-medium hover:bg-gray-50 transition-colors">
                    Batal
                </button>
                <form method="POST" action="{{ $action }}" class="flex-1">
                    @csrf
                    @method($method)
                    <button type="submit"
                        class="w-full px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-xl text-sm font-medium transition-colors">
                        Ya, Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
