<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    Manajemen Warung (Sistem Pusat)
                </h2>
                <p class="text-sm text-gray-500 mt-1">Pantau dan kelola profil seluruh cabang atau mitra warung di
                    ekosistem WARSA.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Alert Sukses --}}
            @if (session('success'))
                <div
                    class="p-4 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center gap-3 shadow-sm animate-fade-in-up">
                    <div class="p-1 bg-emerald-500 rounded-full text-white flex-shrink-0">
                        <x-lucide-check class="w-4 h-4" />
                    </div>
                    <p class="text-sm font-medium text-emerald-800">{{ session('success') }}</p>
                </div>
            @endif

            {{-- Card Container Utama --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                @if (auth()->user()->isSuperAdmin())
                    <div class="px-6 py-5 border-b border-gray-50 bg-gray-50/50 flex items-center justify-between">
                        <h3 class="font-bold text-gray-800 flex items-center gap-2">
                            <x-lucide-store class="w-5 h-5 text-emerald-500" /> Database Warung Aktif
                        </h3>
                    </div>

                    <div class="p-6">
                        <livewire:super-admin.warung-table />
                    </div>
                @else
                    {{-- Tampilan Akses Ditolak --}}
                    <div class="text-center py-16">
                        <div
                            class="w-20 h-20 bg-rose-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-rose-100">
                            <x-lucide-shield-ban class="w-10 h-10 text-rose-400" />
                        </div>
                        <h4 class="text-lg font-bold text-gray-800 mb-1">Akses Ditolak</h4>
                        <p class="text-gray-500 text-sm font-medium max-w-md mx-auto">
                            Halaman ini berada di area terlarang. Anda tidak memiliki hak akses administrator sistem
                            untuk melihat halaman ini.
                        </p>
                        <a href="{{ route('dashboard') }}"
                            class="inline-block mt-6 px-6 py-2.5 bg-gray-900 text-white rounded-xl text-sm font-bold hover:bg-black transition-colors shadow-sm">
                            Kembali ke Dashboard
                        </a>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
