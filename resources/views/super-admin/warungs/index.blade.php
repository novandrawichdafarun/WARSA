<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Warung (Super Admin)') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div
                    class="mb-5 p-4 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center gap-2 shadow-sm">
                    <span class="text-emerald-600 font-bold">✓</span>
                    <p class="text-sm font-medium text-emerald-700">{{ session('success') }}</p>
                </div>
            @endif

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                @if (auth()->user()->isSuperAdmin())
                    <livewire:super-admin.warung-table />
                @else
                    <div class="text-center py-10">
                        <div class="w-14 h-14 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-3">
                            <span class="text-2xl">🚫</span>
                        </div>
                        <p class="text-gray-500 font-medium">Anda tidak memiliki akses untuk melihat halaman ini.</p>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
