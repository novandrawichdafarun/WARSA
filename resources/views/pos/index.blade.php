<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    {{ __('Kasir') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Sistem pencatatan penjualan langsung penjualan warung.</p>
            </div>
        </div>
    </x-slot>

    <div>
        <livewire:pos-kasir />
    </div>
</x-app-layout>
