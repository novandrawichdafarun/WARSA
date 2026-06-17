<div>
    {{-- Search Bar --}}
    <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-4">
        <div class="relative w-full sm:w-80 group">
            <div
                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400 group-focus-within:text-emerald-500 transition-colors">
                <x-lucide-search class="w-4 h-4" />
            </div>
            <input wire:model.live.debounce.300ms="search" type="text"
                class="w-full pl-9 pr-4 py-2.5 bg-gray-50 border border-gray-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 rounded-xl text-sm transition-all font-medium text-gray-800"
                placeholder="Cari nama warung..." />
        </div>
    </div>

    {{-- Tabel Warung Responsif --}}
    <div class="overflow-x-auto border border-gray-100 rounded-2xl shadow-sm">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50/80 border-b border-gray-100">
                    <th class="text-left py-4 px-5 text-[11px] font-bold text-gray-400 uppercase tracking-wider">
                        Identitas Warung</th>
                    <th class="text-left py-4 px-5 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Alamat
                        Lengkap</th>
                    <th class="text-left py-4 px-5 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Kontak
                    </th>
                    <th class="text-center py-4 px-5 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Tim
                    </th>
                    <th class="text-right py-4 px-5 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Aksi
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse ($warungs as $warung)
                    <tr class="hover:bg-emerald-50/30 transition-colors group">

                        {{-- Kolom Nama Warung --}}
                        <td class="py-4 px-5">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 bg-gradient-to-br from-emerald-100 to-teal-100 rounded-xl flex items-center justify-center border border-emerald-200 flex-shrink-0">
                                    <span class="text-emerald-700 font-black text-sm">
                                        {{ strtoupper(substr($warung->nama_warung, 0, 2)) }}
                                    </span>
                                </div>
                                <div>
                                    <p class="font-bold text-gray-800 group-hover:text-emerald-700 transition-colors">
                                        {{ $warung->nama_warung }}</p>
                                    <p class="text-[10px] font-medium text-gray-400 font-mono mt-0.5">
                                        {{ $warung->slug ?? '-' }}</p>
                                </div>
                            </div>
                        </td>

                        {{-- Kolom Alamat --}}
                        <td class="py-4 px-5">
                            <p class="text-xs text-gray-600 font-medium line-clamp-2">
                                {{ $warung->alamat ?? 'Belum diatur' }}</p>
                        </td>

                        {{-- Kolom Telepon --}}
                        <td class="py-4 px-5">
                            <p class="text-xs text-gray-600 font-medium flex items-center gap-1.5">
                                <x-lucide-phone class="w-3.5 h-3.5 text-gray-400" />
                                {{ $warung->telepon ?? '-' }}
                            </p>
                        </td>

                        {{-- Kolom Karyawan Terdaftar (Indikator Cepat) --}}
                        <td class="py-4 px-5 text-center">
                            <span
                                class="px-2.5 py-1 text-[10px] font-bold bg-gray-100 text-gray-600 rounded-md border border-gray-200"
                                title="{{ $warung->users->count() }} Karyawan/Owner terdaftar">
                                {{ $warung->users->count() }} User
                            </span>
                        </td>

                        {{-- Kolom Aksi --}}
                        <td class="py-4 px-5 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button x-data=""
                                    x-on:click.prevent="$dispatch('open-modal', 'edit-warung-{{ $warung->id }}')"
                                    class="p-2 text-amber-500 hover:bg-amber-50 border border-transparent hover:border-amber-200 rounded-lg transition-colors"
                                    title="Edit Warung">
                                    <x-lucide-square-pen class="w-4 h-4" />
                                </button>
                                <button x-data=""
                                    x-on:click.prevent="$dispatch('open-modal', 'delete-warung-{{ $warung->id }}')"
                                    class="p-2 text-rose-500 hover:bg-rose-50 border border-transparent hover:border-rose-200 rounded-lg transition-colors"
                                    title="Hapus Warung">
                                    <x-lucide-trash-2 class="w-4 h-4" />
                                </button>
                            </div>
                        </td>
                    </tr>

                    {{-- ================= MODAL EDIT WARUNG ================= --}}
                    <x-modal name="edit-warung-{{ $warung->id }}" focusable>
                        <form method="POST" action="{{ route('super_admin.warungs.update', $warung->id) }}"
                            class="p-6 text-left">
                            @csrf
                            @method('PUT')

                            {{-- Modal Header --}}
                            <div class="flex items-center gap-4 mb-5 border-b border-gray-50 pb-4">
                                <div
                                    class="w-12 h-12 bg-amber-100 rounded-full flex items-center justify-center border-4 border-white shadow-sm flex-shrink-0">
                                    <x-lucide-store class="w-6 h-6 text-amber-600" />
                                </div>
                                <div>
                                    <h2 class="text-lg font-black text-gray-900">Edit Data Warung</h2>
                                    <p class="text-xs text-gray-500 font-medium mt-0.5">Ubah detail profil warung dan
                                        pengaturan SDM.</p>
                                </div>
                            </div>

                            {{-- Modal Body (Scrollable) --}}
                            <div class="space-y-6 max-h-[70vh] overflow-y-auto px-1">

                                {{-- Section: Detail Profil --}}
                                <div class="space-y-4">
                                    <div>
                                        <label
                                            class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Nama
                                            Warung <span class="text-rose-500">*</span></label>
                                        <input type="text" name="nama_warung" value="{{ $warung->nama_warung }}"
                                            required
                                            class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 rounded-xl text-sm transition-all text-gray-800 font-bold">
                                    </div>

                                    <div>
                                        <label
                                            class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Alamat
                                            Lengkap</label>
                                        <textarea name="alamat" rows="2"
                                            class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 rounded-xl text-sm transition-all text-gray-800 font-medium resize-none">{{ $warung->alamat }}</textarea>
                                    </div>

                                    <div>
                                        <label
                                            class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">No.
                                            Telepon</label>
                                        <input type="text" name="telepon" value="{{ $warung->telepon }}"
                                            placeholder="Contoh: 08123456789"
                                            class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 rounded-xl text-sm transition-all text-gray-800 font-medium">
                                    </div>
                                </div>

                                <div class="border-t border-gray-100"></div>

                                {{-- Section: Manajemen Karyawan --}}
                                <div>
                                    <h3 class="text-sm font-bold text-gray-800 mb-3 flex items-center gap-2">
                                        <x-lucide-users class="w-4 h-4 text-emerald-500" /> Afiliasi Karyawan
                                    </h3>

                                    {{-- List Karyawan Aktif --}}
                                    <div class="bg-gray-50/80 rounded-2xl border border-gray-100 p-4 shadow-inner mb-5">
                                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-3">
                                            Daftar Tim Saat Ini</p>
                                        @if ($warung->users->isEmpty())
                                            <div class="text-center py-3">
                                                <p class="text-xs text-gray-400 italic">Belum ada satupun user yang
                                                    terhubung ke warung ini.</p>
                                            </div>
                                        @else
                                            <div class="space-y-2">
                                                @foreach ($warung->users as $karyawan)
                                                    <div
                                                        class="flex items-center justify-between bg-white px-3 py-2 rounded-xl border border-gray-100 shadow-sm">
                                                        <span
                                                            class="text-xs font-bold text-gray-700">{{ $karyawan->name }}</span>
                                                        <span
                                                            class="px-2.5 py-1 rounded-md text-[9px] font-black uppercase tracking-wider {{ $karyawan->role == 'owner' ? 'bg-blue-100 text-blue-700' : 'bg-emerald-100 text-emerald-700' }}">
                                                            {{ $karyawan->role }}
                                                        </span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Tambah Karyawan Baru --}}
                                    <div class="bg-emerald-50/50 rounded-2xl border border-emerald-100 p-4">
                                        <p class="text-[10px] font-bold text-emerald-600 uppercase tracking-wider mb-3">
                                            Tambahkan User ke Warung Ini</p>
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-xs font-bold text-gray-600 mb-1.5">Pilih User
                                                    <span
                                                        class="text-[10px] text-gray-400 font-normal ml-0.5">(Opsional)</span></label>
                                                <select name="tambah_user_id"
                                                    class="w-full px-3 py-2 bg-white border border-emerald-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 rounded-xl text-xs transition-all cursor-pointer text-gray-700 font-medium">
                                                    <option value="">— Tidak Menambahkan —</option>
                                                    @foreach ($availableUsers as $au)
                                                        <option value="{{ $au->id }}">{{ $au->name }}
                                                            ({{ $au->role }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div>
                                                <label
                                                    class="block text-xs font-bold text-gray-600 mb-1.5">Tugas/Jabatan
                                                    (Role)</label>
                                                <select name="tambah_user_role"
                                                    class="w-full px-3 py-2 bg-white border border-emerald-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 rounded-xl text-xs transition-all cursor-pointer text-gray-700 font-medium">
                                                    <option value="kasir">Kasir</option>
                                                    <option value="owner">Owner (Pemilik)</option>
                                                </select>
                                                @error('tambah_user_role')
                                                    <p class="text-rose-500 text-[10px] mt-1">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Modal Footer --}}
                            <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-50">
                                <button type="button" x-on:click="$dispatch('close')"
                                    class="px-5 py-2.5 border border-gray-200 rounded-xl text-sm font-bold text-gray-600 bg-white hover:bg-gray-50 transition-colors shadow-sm">
                                    Batal
                                </button>
                                <button type="submit"
                                    class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-bold transition-colors shadow-sm active:scale-95 flex items-center gap-2">
                                    <x-lucide-save class="w-4 h-4" /> Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </x-modal>

                    {{-- ================= MODAL HAPUS WARUNG ================= --}}
                    <x-modal name="delete-warung-{{ $warung->id }}" focusable>
                        <form method="POST" action="{{ route('super_admin.warungs.destroy', $warung->id) }}"
                            class="p-6 text-center">
                            @csrf
                            @method('DELETE')

                            <div
                                class="w-16 h-16 bg-rose-100 rounded-full flex items-center justify-center mx-auto mb-4 border-4 border-white shadow-sm">
                                <x-lucide-store class="w-8 h-8 text-rose-500" />
                            </div>
                            <h2 class="text-xl font-black text-gray-900 mb-2 text-center">Hapus Warung Ini?</h2>
                            <p class="text-sm text-gray-500 mb-6 px-4 leading-relaxed">
                                Anda yakin ingin menghapus warung <span
                                    class="font-bold text-gray-800">"{{ $warung->nama_warung }}"</span>? Seluruh data
                                produk, kategori, dan transaksi warung ini akan terhapus.
                            </p>

                            <div class="flex justify-center gap-3">
                                <button type="button" x-on:click="$dispatch('close')"
                                    class="px-5 py-2.5 border border-gray-200 rounded-xl text-sm font-bold text-gray-600 bg-white hover:bg-gray-50 transition-colors">
                                    Batal
                                </button>
                                <button type="submit" x-on:click="$dispatch('close')"
                                    class="px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-sm font-bold transition-colors shadow-sm active:scale-95">
                                    Ya, Hapus Warung
                                </button>
                            </div>
                        </form>
                    </x-modal>

                @empty
                    {{-- Empty State --}}
                    <tr>
                        <td colspan="5" class="py-16 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div
                                    class="w-16 h-16 bg-gray-50 rounded-full border border-gray-100 flex items-center justify-center mb-3">
                                    <x-lucide-store class="w-8 h-8 text-gray-300" />
                                </div>
                                <p class="text-sm font-bold text-gray-600">Tidak ada warung ditemukan</p>
                                <p class="text-xs text-gray-400 mt-1">Belum ada mitra warung yang terdaftar atau
                                    pencarian Anda tidak cocok.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if ($warungs->hasPages())
        <div class="mt-4 pt-4 border-t border-emerald-100">
            {{ $warungs->links() }}
        </div>
    @endif
</div>
