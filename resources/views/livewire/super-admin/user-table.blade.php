<div>
    <div class="mb-4 flex justify-between items-center">
        <div class="w-1/3">
            <x-text-input wire:model.live.debounce.300ms="search" type="text" class="w-full"
                placeholder="Cari nama atau email user..." />
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500 border">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 border-b">Nama</th>
                    <th scope="col" class="px-6 py-3 border-b">Email</th>
                    <th scope="col" class="px-6 py-3 border-b">Role</th>
                    <th scope="col" class="px-6 py-3 border-b">Warung</th>
                    <th scope="col" class="px-6 py-3 border-b text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium text-gray-900">{{ $user->name }}</td>
                        <td class="px-6 py-4">{{ $user->email }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">{{ $user->warung ? $user->warung->nama_warung : '-' }}</td>
                        <td class="px-6 py-4 text-right flex justify-end gap-2">
                            <button x-data=""
                                x-on:click.prevent="$dispatch('open-modal', 'edit-user-{{ $user->id }}')"
                                class="text-indigo-600 hover:text-indigo-900 font-semibold">
                                Edit
                            </button>

                            <button x-data=""
                                x-on:click.prevent="$dispatch('open-modal', 'delete-user-{{ $user->id }}')"
                                class="text-red-600 hover:text-red-900 font-semibold">
                                Hapus
                            </button>
                        </td>
                    </tr>

                    <x-modal name="edit-user-{{ $user->id }}" focusable>
                        <form method="POST" action="{{ route('super_admin.users.update', $user->id) }}"
                            class="p-6 text-left">
                            @csrf
                            @method('PUT')
                            <h2 class="text-lg font-medium text-gray-900 mb-4">Edit Data User</h2>

                            <div class="mb-4">
                                <x-input-label for="name" value="Nama" />
                                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                                    :value="$user->name" required />
                            </div>

                            <div class="mb-4">
                                <x-input-label for="email" value="Email" />
                                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full"
                                    :value="$user->email" required />
                            </div>

                            <div class="mb-4">
                                <x-input-label for="password" value="Password Baru" />
                                <x-text-input id="password" name="password" type="password" class="mt-1 block w-full"
                                    placeholder="Kosongkan jika tidak ingin mengubah password"
                                    autocomplete="new-password" />
                                <p class="text-xs text-gray-500 mt-1">Super Admin tidak dapat melihat password, tetapi
                                    dapat menggantinya.</p>
                            </div>

                            <div class="mb-4">
                                <x-input-label for="role" value="Role" />
                                <select id="role" name="role"
                                    class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full"
                                    required>
                                    <option value="kasir" {{ $user->role == 'kasir' ? 'selected' : '' }}>Kasir</option>
                                    <option value="owner" {{ $user->role == 'owner' ? 'selected' : '' }}>Owner</option>
                                    <option value="super_admin" {{ $user->role == 'super_admin' ? 'selected' : '' }}>
                                        Super Admin</option>
                                </select>
                            </div>

                            <div class="mb-4 mt-6">
                                <label for="is_verified_{{ $user->id }}" class="inline-flex items-center">
                                    <input id="is_verified_{{ $user->id }}" type="checkbox" name="is_verified"
                                        value="1"
                                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                        {{ $user->email_verified_at ? 'checked' : '' }}>
                                    <span class="ms-2 text-sm text-gray-600">Email telah terverifikasi</span>
                                </label>
                            </div>

                            <div class="mb-4">
                                <x-input-label for="warung_id" value="Pilih Warung (Opsional)" />
                                <select id="warung_id" name="warung_id"
                                    class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                                    <option value="">-- Tidak Ada / Super Admin --</option>
                                    @foreach ($warungs as $w)
                                        <option value="{{ $w->id }}"
                                            {{ $user->warung_id == $w->id ? 'selected' : '' }}>
                                            {{ $w->nama_warung }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mt-6 flex justify-end">
                                <x-secondary-button x-on:click="$dispatch('close')">Batal</x-secondary-button>
                                <x-primary-button class="ms-3">Simpan Perubahan</x-primary-button>
                            </div>
                        </form>
                    </x-modal>

                    <x-modal name="delete-user-{{ $user->id }}" focusable>
                        <form method="POST" action="{{ route('super_admin.users.destroy', $user->id) }}"
                            class="p-6 text-left">
                            @csrf
                            @method('DELETE')
                            <h2 class="text-lg font-medium text-gray-900">Apakah Anda yakin ingin menghapus user ini?
                            </h2>
                            <p class="mt-1 text-sm text-gray-600">User: <strong>{{ $user->name }}</strong>
                                ({{ $user->email }})
                                . Data yang dihapus tidak akan bisa mengakses sistem lagi.</p>

                            <div class="mt-6 flex justify-end">
                                <x-secondary-button x-on:click="$dispatch('close')">Batal</x-secondary-button>
                                <x-danger-button class="ms-3">Ya, Hapus</x-danger-button>
                            </div>
                        </form>
                    </x-modal>

                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">Tidak ada data user ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $users->links() }}
    </div>
</div>
