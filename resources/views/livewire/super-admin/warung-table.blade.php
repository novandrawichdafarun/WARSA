<div>
    <div class="mb-4">
        <div class="w-1/3">
            <x-text-input wire:model.live.debounce.300ms="search" type="text" class="w-full"
                placeholder="Cari nama warung..." />
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500 border">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 border-b">Nama Warung</th>
                    <th scope="col" class="px-6 py-3 border-b">Alamat</th>
                    <th scope="col" class="px-6 py-3 border-b">No. Telepon</th>
                    <th scope="col" class="px-6 py-3 border-b text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($warungs as $warung)
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium text-gray-900">{{ $warung->nama_warung }}</td>
                        <td class="px-6 py-4">{{ $warung->alamat ?? '-' }}</td>
                        <td class="px-6 py-4">{{ $warung->telepon ?? '-' }}</td>
                        <td class="px-6 py-4 text-right flex justify-end gap-2">
                            <button x-data=""
                                x-on:click.prevent="$dispatch('open-modal', 'edit-warung-{{ $warung->id }}')"
                                class="text-indigo-600 hover:text-indigo-900 font-semibold">
                                Edit
                            </button>

                            <button x-data=""
                                x-on:click.prevent="$dispatch('open-modal', 'delete-warung-{{ $warung->id }}')"
                                class="text-red-600 hover:text-red-900 font-semibold">
                                Hapus
                            </button>
                        </td>
                    </tr>

                    <x-modal name="edit-warung-{{ $warung->id }}" focusable>
                        <form method="POST" action="{{ route('super_admin.warungs.update', $warung->id) }}"
                            class="p-6 text-left">
                            @csrf
                            @method('PUT')
                            <h2 class="text-lg font-medium text-gray-900 mb-4">Edit Data Warung</h2>

                            <div class="mb-4">
                                <x-input-label for="nama_warung" value="Nama Warung" />
                                <x-text-input id="nama_warung" name="nama_warung" type="text"
                                    class="mt-1 block w-full" :value="$warung->nama_warung" required />
                            </div>

                            <div class="mb-4">
                                <x-input-label for="alamat" value="Alamat" />
                                <x-text-input id="alamat" name="alamat" type="text" class="mt-1 block w-full"
                                    :value="$warung->alamat" />
                            </div>

                            <div class="mb-4">
                                <x-input-label for="telepon" value="No. Telepon" />
                                <x-text-input id="telepon" name="telepon" type="text" class="mt-1 block w-full"
                                    :value="$warung->telepon" />
                            </div>

                            <hr class="my-6 border-gray-200">

                            <h3 class="text-md font-semibold text-gray-900 mb-2">Manajemen Karyawan Warung</h3>

                            <div class="mb-4 p-4 bg-gray-50 rounded-md border text-sm">
                                <p class="font-medium text-gray-700 mb-2">Karyawan Terdaftar:</p>
                                <ul class="list-disc list-inside text-gray-600">
                                    @forelse($warung->users as $karyawan)
                                        <li>
                                            <strong>{{ $karyawan->name }}</strong>
                                            <span
                                                class="px-2 py-0.5 rounded text-xs ml-1 {{ $karyawan->role == 'owner' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                                {{ ucfirst($karyawan->role) }}
                                            </span>
                                        </li>
                                    @empty
                                        <li class="text-gray-400">Belum ada karyawan di warung ini.</li>
                                    @endforelse
                                </ul>
                            </div>

                            <div class="mb-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <x-input-label for="tambah_user_id" value="Pilih User Baru" />
                                    <select id="tambah_user_id" name="tambah_user_id"
                                        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full text-sm">
                                        <option value="">-- Jangan Tambahkan --</option>
                                        @foreach ($availableUsers as $au)
                                            <option value="{{ $au->id }}">{{ $au->name }}
                                                ({{ $au->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <x-input-label for="tambah_user_role" value="Jadikan Sebagai" />
                                    <select id="tambah_user_role" name="tambah_user_role"
                                        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full text-sm">
                                        <option value="kasir">Kasir</option>
                                        <option value="owner">Owner</option>
                                    </select>
                                    <x-input-error class="mt-2" :messages="$errors->get('tambah_user_role')" />
                                </div>
                            </div>

                            <div class="mt-6 flex justify-end">
                                <x-secondary-button x-on:click="$dispatch('close')">Batal</x-secondary-button>
                                <x-primary-button class="ms-3">Simpan Perubahan</x-primary-button>
                            </div>
                        </form>
                    </x-modal>

                    <x-modal name="delete-warung-{{ $warung->id }}" focusable>
                        <form method="POST" action="{{ route('super_admin.warungs.destroy', $warung->id) }}"
                            class="p-6 text-left">
                            @csrf
                            @method('DELETE')
                            <h2 class="text-lg font-medium text-gray-900">Apakah Anda yakin ingin menghapus warung ini?
                            </h2>
                            <p class="mt-1 text-sm text-gray-600">Warung: <strong>{{ $warung->nama_warung }}</strong>.
                                Tindakan ini akan menghapus data warung terkait.</p>

                            <div class="mt-6 flex justify-end">
                                <x-secondary-button x-on:click="$dispatch('close')">Batal</x-secondary-button>
                                <x-danger-button class="ms-3">Ya, Hapus</x-danger-button>
                            </div>
                        </form>
                    </x-modal>

                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">Tidak ada data warung ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $warungs->links() }}
    </div>
</div>
