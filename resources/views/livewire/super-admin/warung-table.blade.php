<div>
    {{-- Search Bar --}}
    <div class="mb-6 flex flex-col md:flex-row justify-between items-center gap-4">
        <div class="relative w-full md:w-80">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                🔍
            </span>
            <input wire:model.live.debounce.300ms="search" type="text"
                class="pl-9 pr-4 py-2.5 bg-gray-50 border border-gray-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 rounded-xl text-sm w-full transition-all"
                placeholder="Cari nama warung..." />
        </div>
    </div>

    {{-- Tabel Warung Responsif --}}
    <div class="overflow-x-auto -mx-6 md:mx-0">
        <div class="inline-block min-w-full align-middle md:px-0 px-6">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50/70">
                        <th class="py-3.5 px-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Nama Warung
                        </th>
                        <th class="py-3.5 px-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Alamat</th>
                        <th class="py-3.5 px-4 text-xs font-bold text-gray-400 uppercase tracking-wider">No. Telepon
                        </th>
                        <th
                            class="py-3.5 px-4 text-xs font-bold text-gray-400 uppercase tracking-wider text-right w-40">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($warungs as $warung)
                        <tr class="hover:bg-gray-50/60 transition-colors group">
                            <td class="py-4 px-4 font-bold text-gray-800 text-sm">{{ $warung->nama_warung }}</td>
                            <td class="py-4 px-4 text-sm text-gray-600">{{ $warung->alamat ?? '-' }}</td>
                            <td class="py-4 px-4 text-sm text-gray-600">{{ $warung->telepon ?? '-' }}</td>
                            <td class="py-4 px-4 whitespace-nowrap text-sm font-medium text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button x-data=""
                                        x-on:click.prevent="$dispatch('open-modal', 'edit-warung-{{ $warung->id }}')"
                                        class="inline-flex items-center px-3 py-1.5 bg-white border border-gray-200 hover:border-blue-300 text-blue-600 hover:bg-blue-50 text-xs font-semibold rounded-lg shadow-sm transition-all">
                                        <x-lucide-square-pen class="w-3 h-3 mr-0.5" /> Edit
                                    </button>
                                    <button x-data=""
                                        x-on:click.prevent="$dispatch('open-modal', 'delete-warung-{{ $warung->id }}')"
                                        class="inline-flex items-center px-3 py-1.5 bg-white border border-gray-200 hover:border-red-300 text-red-600 hover:bg-red-50 text-xs font-semibold rounded-lg shadow-sm transition-all">
                                        <x-lucide-trash-2 class="w-3 h-3 mr-0.5" /> Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>

                        {{-- Modal Edit Warung --}}
                        <x-modal name="edit-warung-{{ $warung->id }}" focusable>
                            <form method="POST" action="{{ route('super_admin.warungs.update', $warung->id) }}"
                                class="p-6 text-left">
                                @csrf
                                @method('PUT')

                                <div class="mb-6">
                                    <h2 class="text-xl font-bold text-gray-800">Edit Data Warung</h2>
                                    <p class="text-sm text-gray-500 mt-1">Perbarui profil informasi warung dan
                                        penempatan karyawan</p>
                                </div>

                                <div class="space-y-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Warung</label>
                                        <input type="text" name="nama_warung" value="{{ $warung->nama_warung }}"
                                            required
                                            class="block w-full border border-gray-300 rounded-lg shadow-sm text-sm focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 bg-white px-4 py-2.5 transition-colors">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
                                        <input type="text" name="alamat" value="{{ $warung->alamat }}"
                                            class="block w-full border border-gray-300 rounded-lg shadow-sm text-sm focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 bg-white px-4 py-2.5 transition-colors">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">No Telepon</label>
                                        <input type="text" name="telepon" value="{{ $warung->telepon }}"
                                            class="block w-full border border-gray-300 rounded-lg shadow-sm text-sm focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 bg-white px-4 py-2.5 transition-colors">
                                    </div>
                                </div>

                                <div class="my-8 border-t border-gray-200"></div>

                                <div class="mb-6">
                                    <h3 class="text-lg font-bold text-gray-800 mb-4">Manajemen Karyawan Warung</h3>

                                    <div class="bg-gray-50 rounded-xl border border-gray-200 p-5 shadow-sm mb-6">
                                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-4">
                                            Karyawan Terdaftar</p>
                                        @if ($warung->users->isEmpty())
                                            <p class="text-sm text-gray-400 italic">Belum ada karyawan di warung ini</p>
                                        @else
                                            <div class="space-y-3">
                                                @foreach ($warung->users as $karyawan)
                                                    <div
                                                        class="flex items-center justify-between bg-white px-4 py-3 rounded-lg border border-gray-100 shadow-sm">
                                                        <span
                                                            class="text-sm font-semibold text-gray-800">{{ $karyawan->name }}</span>
                                                        <span
                                                            class="px-3 py-1 rounded-md text-xs font-bold uppercase tracking-wider {{ $karyawan->role == 'owner' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' }}">
                                                            {{ $karyawan->role }}
                                                        </span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Pilih User
                                                Baru</label>
                                            <select name="tambah_user_id"
                                                class="block w-full border border-gray-300 rounded-lg shadow-sm text-sm focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 bg-white px-4 py-2.5 transition-colors">
                                                <option value="">Tidak Ada</option>
                                                @foreach ($availableUsers as $au)
                                                    <option value="{{ $au->id }}">{{ $au->name }}
                                                        ({{ $au->email }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Jadikan
                                                Sebagai</label>
                                            <select name="tambah_user_role"
                                                class="block w-full border border-gray-300 rounded-lg shadow-sm text-sm focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 bg-white px-4 py-2.5 transition-colors">
                                                <option value="kasir">Kasir</option>
                                                <option value="owner">Owner</option>
                                            </select>
                                            @error('tambah_user_role')
                                                <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-8 flex justify-end gap-4 pt-6 border-t border-gray-100">
                                    <button type="button" x-on:click="$dispatch('close')"
                                        class="px-6 py-2.5 border border-gray-300 rounded-lg text-sm font-bold text-gray-700 bg-white hover:bg-gray-50 transition-colors shadow-sm">
                                        Batal
                                    </button>
                                    <button type="submit"
                                        class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-bold transition-colors shadow-sm">
                                        Simpan Perubahan
                                    </button>
                                </div>
                            </form>
                        </x-modal>

                        {{-- Modal Delete Warung --}}
                        <x-modal name="delete-warung-{{ $warung->id }}" focusable>
                            <form method="POST" action="{{ route('super_admin.warungs.destroy', $warung->id) }}"
                                class="p-6 text-center">
                                @csrf
                                @method('DELETE')
                                <div
                                    class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <x-lucide-store class="w-8 h-8 text-red-500" />
                                </div>
                                <h2 class="text-xl font-bold text-gray-900 mb-2">Hapus Warung Ini?</h2>
                                <p class="text-sm text-gray-500 mb-6">
                                    Anda akan menghapus <strong>{{ $warung->nama_warung }}</strong> beserta datanya.
                                    Tindakan ini tidak dapat dibatalkan.
                                </p>

                                <div class="flex justify-center gap-3">
                                    <button type="button" x-on:click="$dispatch('close')"
                                        class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                                        Batal
                                    </button>
                                    <button type="submit"
                                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-medium transition-colors shadow-sm">
                                        Ya, Hapus Warung
                                    </button>
                                </div>
                            </form>
                        </x-modal>

                    @empty
                        {{-- Empty State --}}
                        <tr>
                            <td colspan="4" class="text-center py-12">
                                <div class="max-w-xs mx-auto flex flex-col items-center">
                                    <div
                                        class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-3 border border-gray-100">
                                        <span class="text-3xl text-gray-400">🏪</span>
                                    </div>
                                    <p class="text-sm font-bold text-gray-600">Tidak ada warung ditemukan</p>
                                    <p class="text-xs text-gray-400 mt-1">Belum ada data warung yang dibuat atau
                                        dicari.
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6 pt-4 border-t border-gray-100">
        {{ $warungs->links() }}
    </div>
</div>
