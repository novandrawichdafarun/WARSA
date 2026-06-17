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
                placeholder="Cari nama atau email user..." />
        </div>
    </div>

    {{-- Tabel User Responsif --}}
    <div class="overflow-x-auto border border-gray-100 rounded-2xl shadow-sm">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50/80 border-b border-gray-100">
                    <th class="text-left py-4 px-5 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Info
                        Pengguna</th>
                    <th class="text-left py-4 px-5 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Role
                        Akses</th>
                    <th class="text-left py-4 px-5 text-[11px] font-bold text-gray-400 uppercase tracking-wider">
                        Afiliasi Warung</th>
                    <th class="text-left py-4 px-5 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Tgl.
                        Terdaftar</th>
                    <th class="text-left py-4 px-5 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Tgl.
                        Status</th>
                    <th class="text-right py-4 px-5 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Aksi
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse ($users as $user)

                    {{-- 1. Menyembunyikan Super Admin dari Tabel --}}
                    @if ($user->role === 'super_admin')
                        @continue
                    @endif

                    <tr class="hover:bg-emerald-50/30 transition-colors group">

                        {{-- Kolom Info User --}}
                        <td class="py-3 px-5">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 bg-gradient-to-br from-emerald-100 to-teal-100 rounded-full flex items-center justify-center border border-emerald-200 flex-shrink-0">
                                    <span class="text-emerald-700 font-black text-sm">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </span>
                                </div>
                                <div>
                                    <p class="font-bold text-gray-800 group-hover:text-emerald-700 transition-colors">
                                        {{ $user->name }}</p>
                                    <p class="text-[11px] font-medium text-gray-500 mt-0.5">{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>

                        {{-- Kolom Role --}}
                        <td class="py-3 px-5">
                            @php
                                $roleColors = [
                                    'owner' => 'bg-purple-100 text-purple-700 border-purple-200',
                                    'kasir' => 'bg-blue-100 text-blue-700 border-blue-200',
                                    'pelanggan' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                ];
                                $colorClass = $roleColors[$user->role] ?? 'bg-gray-100 text-gray-700 border-gray-200';
                            @endphp
                            <span
                                class="px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider rounded-md border {{ $colorClass }}">
                                {{ str_replace('_', ' ', $user->role) }}
                            </span>
                        </td>

                        {{-- Kolom Warung --}}
                        <td class="py-3 px-5">
                            @if ($user->warung)
                                <p class="font-bold text-gray-700 text-xs">{{ $user->warung->nama_warung }}</p>
                                <p class="text-[10px] text-gray-400 mt-0.5 font-mono">{{ $user->warung->slug }}</p>
                            @else
                                <span
                                    class="text-gray-400 text-[11px] italic font-semibold px-2 py-1 bg-gray-50 rounded-md border border-gray-100">Belum
                                    Terhubung</span>
                            @endif
                        </td>

                        {{-- Kolom Tanggal --}}
                        <td class="py-3 px-5 text-gray-500 text-xs font-medium">
                            {{ $user->created_at->format('d M Y') }}
                        </td>

                        <td class="py-3 px-5 whitespace-nowrap">
                            @if ($user->email_verified_at)
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>
                                    Terverifikasi
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5"></span>
                                    Belum
                                </span>
                            @endif
                        </td>

                        {{-- Kolom Aksi --}}
                        <td class="py-3 px-5 text-right">
                            <div class="flex items-center justify-end gap-2">

                                {{-- Tombol Buka Modal Edit --}}
                                <button x-data=""
                                    x-on:click.prevent="$dispatch('open-modal', 'edit-user-{{ $user->id }}')"
                                    class="p-2 text-amber-500 hover:bg-amber-50 border border-transparent hover:border-amber-200 rounded-lg transition-colors"
                                    title="Edit User">
                                    <x-lucide-square-pen class="w-4 h-4" />
                                </button>

                                {{-- Tombol Buka Modal Hapus --}}
                                <button x-data=""
                                    x-on:click.prevent="$dispatch('open-modal', 'delete-user-{{ $user->id }}')"
                                    class="p-2 text-rose-500 hover:bg-rose-50 border border-transparent hover:border-rose-200 rounded-lg transition-colors"
                                    title="Hapus User">
                                    <x-lucide-trash-2 class="w-4 h-4" />
                                </button>

                            </div>
                        </td>
                    </tr>

                    {{-- ================= MODAL EDIT USER ================= --}}
                    <x-modal name="edit-user-{{ $user->id }}" focusable>
                        <form method="POST" action="{{ route('super_admin.users.update', $user->id) }}"
                            class="p-4 text-left">
                            @csrf
                            @method('PUT')

                            {{-- Modal Header --}}
                            <div class="flex items-center gap-4 mb-2 border-b border-gray-50 pb-2">
                                <div
                                    class="w-12 h-12 bg-amber-100 rounded-full flex items-center justify-center border-4 border-white shadow-sm flex-shrink-0">
                                    <x-lucide-user-pen class="w-6 h-6 text-amber-600" />
                                </div>
                                <div>
                                    <h2 class="text-lg font-black text-gray-900">Edit Data Pengguna</h2>
                                    <p class="text-xs text-gray-500 font-medium mt-0.5">Ubah profil atau hak akses milik
                                        <span class="font-bold">{{ $user->name }}</span>.
                                    </p>
                                </div>
                            </div>

                            {{-- Modal Body (Form Fields) --}}
                            <div class="space-y-4 max-h-[70vh] overflow-y-auto px-1">
                                <div>
                                    <label
                                        class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Nama
                                        Lengkap <span class="text-rose-500">*</span></label>
                                    <input type="text" name="name" value="{{ $user->name }}" required
                                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 rounded-xl text-sm transition-all text-gray-800 font-medium">
                                </div>

                                <div>
                                    <label
                                        class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Alamat
                                        Email <span class="text-rose-500">*</span></label>
                                    <input type="email" name="email" value="{{ $user->email }}" required
                                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 rounded-xl text-sm transition-all text-gray-800 font-medium">
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label
                                            class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Role
                                            Akses <span class="text-rose-500">*</span></label>
                                        <select name="role" required
                                            class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 rounded-xl text-sm transition-all cursor-pointer text-gray-700 font-medium">
                                            <option value="owner" {{ $user->role === 'owner' ? 'selected' : '' }}>
                                                Owner (Pemilik)</option>
                                            <option value="kasir" {{ $user->role === 'kasir' ? 'selected' : '' }}>
                                                Kasir</option>
                                            <option value="pelanggan"
                                                {{ $user->role === 'pelanggan' ? 'selected' : '' }}>Pelanggan</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label
                                            class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Afiliasi
                                            Warung</label>
                                        <select name="warung_id"
                                            class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 rounded-xl text-sm transition-all cursor-pointer text-gray-700 font-medium">
                                            <option value="">— Tidak Terafiliasi —</option>
                                            @if (isset($warungs))
                                                @foreach ($warungs as $w)
                                                    <option value="{{ $w->id }}"
                                                        {{ $user->warung_id == $w->id ? 'selected' : '' }}>
                                                        {{ $w->nama_warung }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>

                                <div class="pt-2">
                                    <label
                                        class="flex items-start p-4 bg-gray-50/70 rounded-xl border border-gray-200 cursor-pointer hover:bg-gray-100 transition-colors shadow-sm">
                                        <div class="flex items-center h-5 mt-0.5">
                                            <input type="checkbox" name="is_verified" value="1"
                                                class="w-5 h-5 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500 focus:ring-2 shadow-sm"
                                                {{ $user->email_verified_at ? 'checked' : '' }}>
                                        </div>
                                        <div class="ml-3 flex-1">
                                            <span class="block text-sm font-bold text-gray-800">Verifikasi Email
                                                Manual</span>
                                            <span class="block text-xs text-gray-500 mt-0.5">Centang untuk menandai
                                                email pengguna ini sudah aktif dan sah.</span>
                                        </div>
                                    </label>
                                </div>

                                <div class="p-4 bg-gray-50/80 rounded-xl border border-gray-100 mt-2">
                                    <label
                                        class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Ganti
                                        Password <span
                                            class="text-[10px] text-gray-400 font-normal ml-1">(Opsional)</span></label>
                                    <input type="password" name="password"
                                        placeholder="Kosongkan jika tidak ingin diubah..."
                                        class="w-full px-4 py-2.5 bg-white border border-gray-200 focus:border-amber-500 focus:ring-2 focus:ring-amber-100 rounded-lg text-sm transition-all placeholder-gray-400">
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

                    {{-- ================= MODAL HAPUS USER ================= --}}
                    <x-modal name="delete-user-{{ $user->id }}" focusable>
                        <form method="POST" action="{{ route('super_admin.users.destroy', $user->id) }}"
                            class="p-6 text-center">
                            @csrf
                            @method('DELETE')
                            <div
                                class="w-16 h-16 bg-rose-100 rounded-full flex items-center justify-center mx-auto mb-4 border-4 border-white shadow-sm">
                                <x-lucide-triangle-alert class="w-8 h-8 text-rose-500" />
                            </div>
                            <h2 class="text-xl font-black text-gray-900 mb-2 text-center">Hapus Pengguna Ini?</h2>
                            <p class="text-sm text-gray-500 mb-6 px-4 leading-relaxed">
                                Anda yakin ingin menghapus akun <span
                                    class="font-bold text-gray-800">"{{ $user->name }}"</span>? Seluruh akses dan
                                datanya akan dihilangkan permanen dari ekosistem.
                            </p>

                            <div class="flex justify-center gap-3">
                                <button type="button" x-on:click="$dispatch('close')"
                                    class="px-5 py-2.5 border border-gray-200 rounded-xl text-sm font-bold text-gray-600 bg-white hover:bg-gray-50 transition-colors">
                                    Batal
                                </button>
                                <button type="submit"
                                    class="px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-sm font-bold transition-colors shadow-sm active:scale-95">
                                    Ya, Hapus User
                                </button>
                            </div>
                        </form>
                    </x-modal>

                @empty
                    <tr>
                        <td colspan="5" class="py-16 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div
                                    class="w-16 h-16 bg-gray-50 rounded-full border border-gray-100 flex items-center justify-center mb-3">
                                    <x-lucide-users class="w-8 h-8 text-gray-300" />
                                </div>
                                <p class="text-sm font-bold text-gray-600">Tidak ada pengguna lain</p>
                                <p class="text-xs text-gray-400 mt-1">Belum ada pengguna terdaftar atau pencarian Anda
                                    tidak cocok.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if ($users->hasPages())
        <div class="mt-4 pt-4 border-t border-gray-100">
            {{ $users->links() }}
        </div>
    @endif
</div>
