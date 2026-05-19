<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    {{ __('Kasir / POS') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Sistem pencatatan penjualan langsung penjualan warung.</p>
            </div>
        </div>
    </x-slot>

    <div>
        <livewire:pos-kasir />
    </div>
</x-app-layout>
