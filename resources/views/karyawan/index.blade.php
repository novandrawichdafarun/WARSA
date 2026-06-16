<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    Manajemen Karyawan
                </h2>
                <p class="text-sm text-gray-500 mt-1">Kelola akun kasir dan pelanggan warung Anda.</p>
            </div>
            <div>
                <a href="{{ route('karyawan.create') }}"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-xl shadow-sm transition-all hover:shadow-md transform active:scale-95">
                    <x-lucide-plus class="w-5 h-5" /> Tambah Akun Karyawan
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Pesan Sukses --}}
            @if (session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center gap-3 shadow-sm">
                    <div class="p-1 bg-emerald-500 rounded-full text-white">
                        <x-lucide-check class="w-4 h-4" />
                    </div>
                    <p class="text-sm font-medium text-emerald-800">{{ session('success') }}</p>
                </div>
            @endif

            {{-- Daftar Karyawan --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-50 flex items-center justify-between bg-gray-50/50">
                    <h3 class="font-bold text-gray-800 flex items-center gap-2">
                        <x-lucide-users class="w-5 h-5 text-emerald-500" /> Daftar Karyawan Terdaftar
                        <span
                            class="ml-2 px-2.5 py-0.5 bg-gray-200 text-gray-600 text-xs rounded-full font-bold">{{ $karyawan->count() }}</span>
                    </h3>
                </div>

                @if ($karyawan->isEmpty())
                    <div class="text-center py-16">
                        <div
                            class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-gray-100">
                            <x-lucide-user-x class="w-10 h-10 text-gray-300" />
                        </div>
                        <h4 class="text-base font-bold text-gray-700 mb-1">Belum Ada Karyawan</h4>
                        <p class="text-gray-400 text-sm mb-6">Anda belum menambahkan akun kasir atau pelanggan.</p>
                        <a href="{{ route('karyawan.create') }}"
                            class="inline-block px-5 py-2.5 bg-emerald-50 text-emerald-600 font-bold rounded-xl text-sm hover:bg-emerald-100 transition-colors">
                            + Tambah Karyawan Pertama
                        </a>
                    </div>
                @else
                    <div class="divide-y divide-gray-50">
                        @foreach ($karyawan as $user)
                            <div
                                class="flex flex-col sm:flex-row sm:items-center justify-between p-6 hover:bg-emerald-50/30 transition-colors group">

                                {{-- Info User --}}
                                <div class="flex items-center gap-4 mb-4 sm:mb-0">
                                    <div
                                        class="w-12 h-12 bg-gradient-to-br from-emerald-100 to-teal-100 rounded-full flex items-center justify-center border border-emerald-200 shadow-sm">
                                        <span class="text-emerald-700 font-black text-lg">
                                            {{ strtoupper(substr($user->name, 0, 2)) }}
                                        </span>
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-800 text-base flex items-center gap-2">
                                            {{ $user->name }}
                                            <span
                                                class="px-1.5 text-[10px] rounded-full font-bold tracking-wider uppercase
                                                {{ $user->role === 'kasir' ? 'bg-blue-100 text-blue-700 border border-blue-200' : 'bg-orange-100 text-orange-700 border border-orange-200' }}">
                                                {{ $user->role }}
                                            </span>
                                        </p>
                                        <p class="text-gray-500 text-sm flex items-center gap-1 mt-0.5">
                                            <x-lucide-mail class="w-3.5 h-3.5 text-gray-400" /> {{ $user->email }}
                                        </p>
                                    </div>
                                </div>

                                {{-- Aksi & Status --}}
                                <div class="flex items-center gap-3">
                                    <span class="text-xs font-medium text-gray-400 hidden md:block mr-4">
                                        Bergabung {{ $user->created_at->diffForHumans() }}
                                    </span>

                                    <a href="{{ route('karyawan.edit', $user) }}"
                                        class="inline-flex items-center justify-center w-9 h-9 bg-white border border-gray-200 hover:border-amber-300 text-amber-500 hover:bg-amber-50 rounded-xl shadow-sm transition-all"
                                        title="Edit Akun">
                                        <x-lucide-square-pen class="w-4 h-4" />
                                    </a>

                                    <button x-data=""
                                        x-on:click.prevent="$dispatch('open-modal', 'delete-user-{{ $user->id }}')"
                                        class="inline-flex items-center justify-center w-9 h-9 bg-white border border-gray-200 hover:border-rose-300 text-rose-500 hover:bg-rose-50 rounded-xl shadow-sm transition-all"
                                        title="Hapus Akun">
                                        <x-lucide-trash-2 class="w-4 h-4" />
                                    </button>
                                </div>
                            </div>

                            {{-- Modal Hapus --}}
                            <x-modal name="delete-user-{{ $user->id }}" focusable>
                                <form method="POST" action="{{ route('karyawan.destroy', $user) }}"
                                    class="p-6 text-center">
                                    @csrf
                                    @method('DELETE')
                                    <div
                                        class="w-16 h-16 bg-rose-100 rounded-full flex items-center justify-center mx-auto mb-4 border-4 border-white shadow-sm">
                                        <x-lucide-triangle-alert class="w-8 h-8 text-rose-500" />
                                    </div>
                                    <h2 class="text-xl font-black text-gray-900 mb-2">Hapus Akun Karyawan?</h2>
                                    <p class="text-sm text-gray-500 mb-6 px-4">
                                        Apakah Anda yakin ingin menghapus akun <span
                                            class="font-bold text-gray-800">"{{ $user->name }}"</span>? Data yang
                                        telah dihapus tidak dapat dikembalikan.
                                    </p>

                                    <div class="flex justify-center gap-3">
                                        <button type="button" x-on:click="$dispatch('close')"
                                            class="px-5 py-2.5 border border-gray-200 rounded-xl text-sm font-bold text-gray-600 bg-white hover:bg-gray-50 transition-colors">
                                            Batal
                                        </button>
                                        <button type="submit"
                                            class="px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-sm font-bold transition-colors shadow-sm active:scale-95">
                                            Ya, Hapus Permanen
                                        </button>
                                    </div>
                                </form>
                            </x-modal>
                        @endforeach
                    </div>
                @endif

                {{-- Pagination --}}
                @if ($karyawan->hasPages())
                    <div class="p-5 border-t border-gray-50 bg-gray-50/30">
                        {{ $karyawan->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
