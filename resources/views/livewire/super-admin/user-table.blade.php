<div>
    <div class="mb-6 flex flex-col md:flex-row justify-between items-center gap-4">
        <div class="relative w-full md:w-80">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                🔍
            </span>
            <input wire:model.live.debounce.300ms="search" type="text"
                class="pl-9 pr-4 py-2.5 bg-gray-50 border border-gray-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 rounded-xl text-sm w-full transition-all"
                placeholder="Cari nama atau email user..." />
        </div>
    </div>

    {{-- Tabel User Responsif --}}
    <div class="overflow-x-auto -mx-6 md:mx-0">
        <div class="inline-block min-w-full align-middle md:px-0 px-6">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50/70">
                        <th class="py-3.5 px-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Nama</th>
                        <th class="py-3.5 px-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Email</th>
                        <th class="py-3.5 px-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Role</th>
                        <th class="py-3.5 px-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Warung</th>
                        <th class="py-3.5 px-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Status</th>
                        <th
                            class="py-3.5 px-4 text-xs font-bold text-gray-400 uppercase tracking-wider text-right w-40">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($users as $user)
                        @if ($user->role !== 'super_admin')
                            <tr class="hover:bg-gray-50/60 transition-colors group">
                                <td class="py-4 px-4 font-semibold text-gray-800 text-sm">{{ $user->name }}</td>
                                <td class="py-4 px-4 text-sm text-gray-600">{{ $user->email }}</td>
                                <td class="py-4 px-4 text-sm">
                                    <span
                                        class="px-2.5 py-1 {{ $user->role == 'owner' ? 'bg-purple-50 text-purple-700' : 'bg-blue-50 text-blue-700' }} rounded-lg text-xs font-semibold">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td class="py-4 px-4 text-sm text-gray-600 font-medium">
                                    {{ $user->warung ? $user->warung->nama_warung : '-' }}
                                </td>
                                <td class="py-4 px-4 whitespace-nowrap">
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
                                <td class="py-4 px-4 whitespace-nowrap text-sm font-medium text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button x-data=""
                                            x-on:click.prevent="$dispatch('open-modal', 'edit-user-{{ $user->id }}')"
                                            class="inline-flex items-center px-3 py-1.5 bg-white border border-gray-200 hover:border-blue-300 text-blue-600 hover:bg-blue-50 text-xs font-semibold rounded-lg shadow-sm transition-all">
                                            <x-lucide-square-pen class="w-3 h-3 mr-0.5" /> Edit
                                        </button>
                                        <button x-data=""
                                            x-on:click.prevent="$dispatch('open-modal', 'delete-user-{{ $user->id }}')"
                                            class="inline-flex items-center px-3 py-1.5 bg-white border border-gray-200 hover:border-red-300 text-red-600 hover:bg-red-50 text-xs font-semibold rounded-lg shadow-sm transition-all">
                                            <x-lucide-trash-2 class="w-3 h-3 mr-0.5" /> Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endif

                        {{-- Modal Edit User --}}
                        <x-modal name="edit-user-{{ $user->id }}" focusable>
                            <form method="POST" action="{{ route('super_admin.users.update', $user->id) }}"
                                class="p-6 text-left">
                                @csrf
                                @method('PUT')

                                <!-- Bagian Header Modal -->
                                <div class="mb-6">
                                    <h2 class="text-xl font-bold text-gray-800">Edit Data User</h2>
                                    <p class="text-sm text-gray-500 mt-1">Perbarui informasi dan hak akses untuk
                                        pengguna ini.</p>
                                </div>

                                <!-- Bagian Input Form -->
                                <div class="space-y-5">
                                    <!-- Nama Lengkap -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                                        <input type="text" name="name" value="{{ $user->name }}" required
                                            class="block w-full border-gray-300 rounded-lg shadow-sm text-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 focus:ring-opacity-50 transition-colors">
                                    </div>

                                    <!-- Email -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                        <input type="email" name="email" value="{{ $user->email }}" required
                                            class="block w-full border-gray-300 bg-gray-50 rounded-lg shadow-sm text-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 focus:ring-opacity-50 transition-colors">
                                    </div>

                                    <!-- Password -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Password Baru <span
                                                class="text-gray-400 font-normal">(Opsional)</span></label>
                                        <input type="password" name="password" autocomplete="new-password"
                                            placeholder="Kosongkan jika tidak diubah"
                                            class="block w-full border-gray-300 rounded-lg shadow-sm text-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 focus:ring-opacity-50 transition-colors">
                                    </div>

                                    <!-- Role & Warung (2 Kolom) -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                                            <select name="role" required
                                                class="block w-full border-gray-300 rounded-lg shadow-sm text-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 focus:ring-opacity-50 bg-white transition-colors">
                                                <option value="kasir" {{ $user->role == 'kasir' ? 'selected' : '' }}>
                                                    Kasir</option>
                                                <option value="owner" {{ $user->role == 'owner' ? 'selected' : '' }}>
                                                    Owner</option>
                                                <option value="super_admin"
                                                    {{ $user->role == 'super_admin' ? 'selected' : '' }}>Super Admin
                                                </option>
                                            </select>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Pilih
                                                Warung</label>
                                            <select name="warung_id"
                                                class="block w-full border-gray-300 rounded-lg shadow-sm text-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 focus:ring-opacity-50 bg-white transition-colors">
                                                <option value="">-- Tidak Ada --</option>
                                                @foreach ($warungs as $w)
                                                    <option value="{{ $w->id }}"
                                                        {{ $user->warung_id == $w->id ? 'selected' : '' }}>
                                                        {{ $w->nama_warung }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Checkbox Verifikasi Manual -->
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
                                </div>

                                <!-- Bagian Footer (Tombol) -->
                                <div class="mt-8 pt-5 border-t border-gray-100 flex justify-end gap-3">
                                    <button type="button" x-on:click="$dispatch('close')"
                                        class="px-5 py-2.5 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors shadow-sm">
                                        Batal
                                    </button>
                                    <button type="submit"
                                        class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-medium transition-colors shadow-sm">
                                        Simpan Perubahan
                                    </button>
                                </div>
                            </form>
                        </x-modal>

                        {{-- Modal Delete User --}}
                        <x-modal name="delete-user-{{ $user->id }}" focusable>
                            <form method="POST" action="{{ route('super_admin.users.destroy', $user->id) }}"
                                class="p-6 text-center">
                                @csrf
                                @method('DELETE')
                                <div
                                    class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <x-lucide-triangle-alert stroke-width="2" class="w-8 h-8 text-red-500" />
                                </div>
                                <h2 class="text-xl font-bold text-gray-900 mb-2">Hapus User Ini?</h2>
                                <p class="text-sm text-gray-500 mb-6">
                                    Anda akan menghapus akses untuk <strong>{{ $user->name }}</strong>
                                    ({{ $user->email }})
                                    . Tindakan ini tidak dapat dibatalkan.
                                </p>

                                <div class="flex justify-center gap-3">
                                    <button type="button" x-on:click="$dispatch('close')"
                                        class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                                        Batal
                                    </button>
                                    <button type="submit"
                                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-medium transition-colors shadow-sm">
                                        Ya, Hapus User
                                    </button>
                                </div>
                            </form>
                        </x-modal>

                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-12">
                                <div class="max-w-xs mx-auto flex flex-col items-center">
                                    <div
                                        class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-3 border border-gray-100">
                                        <span class="text-3xl text-gray-400">👥</span>
                                    </div>
                                    <p class="text-sm font-bold text-gray-600">Tidak ada user ditemukan</p>
                                    <p class="text-xs text-gray-400 mt-1">Belum ada user yang terdaftar atau pencarian
                                        tidak cocok.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6 pt-4 border-t border-gray-100">
        {{ $users->links() }}
    </div>
</div>
