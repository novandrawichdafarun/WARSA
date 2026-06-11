<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800">Manajemen Karyawan</h2>
            <a href="{{ route('karyawan.create') }}"
                class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-medium transition-colors shadow-sm">
                + Tambah Karyawan
            </a>
        </div>
    </x-slot>

    @if ($karyawan->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $karyawan->links() }}
        </div>
    @endif

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-5 p-4 bg-green-50 border border-green-200 rounded-lg flex items-center gap-2">
                    <span class="text-green-600">✓</span>
                    <p class="text-sm text-green-700">{{ session('success') }}</p>
                </div>
            @endif

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-medium text-gray-800">
                        Daftar Karyawan
                        <span class="text-sm font-normal text-gray-400">({{ $karyawan->count() }} karyawan)</span>
                    </h3>
                </div>

                @if ($karyawan->isEmpty())
                    <div class="text-center py-16">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <p class="text-gray-400 text-sm mb-4">Belum ada karyawan. Tambahkan akun karyawan pertama!</p>
                        <a href="{{ route('karyawan.create') }}"
                            class="inline-block px-4 py-2 bg-green-600 text-white rounded-lg text-sm hover:bg-green-700 transition-colors">
                            + Tambah Karyawan Pertama
                        </a>
                    </div>
                @else
                    <div class="divide-y divide-gray-50">
                        @foreach ($karyawan as $user)
                            <div class="flex items-center justify-between px-6 py-4 hover:bg-gray-50 transition-colors">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                        <span class="text-blue-700 font-bold text-sm">
                                            {{ strtoupper(substr($user->name, 0, 2)) }}
                                        </span>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-800 text-sm">{{ $user->name }}</p>
                                        <p class="text-gray-400 text-xs">{{ $user->email }}</p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-3">
                                    <span
                                        class="px-2 py-1 bg-blue-100 text-blue-700 text-xs rounded-full font-medium">{{ $user->role === 'kasir' ? 'Kasir' : 'Pelanggan' }}</span>
                                    <span class="text-xs text-gray-400">
                                        Bergabung {{ $user->created_at->diffForHumans() }}
                                    </span>

                                    <a href="{{ route('karyawan.edit', $user) }}"
                                        class="text-xs text-yellow-500 hover:text-yellow-700 px-2 py-1 rounded hover:bg-yellow-50 transition-colors">
                                        Edit
                                    </a>

                                    <form method="POST" action="{{ route('karyawan.destroy', $user) }}"
                                        onsubmit="return confirm('Hapus akun user {{ $user->name }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text-xs text-red-500 hover:text-red-700 px-2 py-1 rounded hover:bg-red-50 transition-colors">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
