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

    <div class="py-16">
        <div class="max-w-xl mx-auto px-6 text-center">
            <div
                class="w-24 h-24 bg-amber-50 text-amber-500 rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-sm border border-amber-100 relative">
                <svg class="w-12 h-12 animate-bounce" fill="none" stroke="currentColor" stroke-width="1.5"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                </svg>
                <span class="absolute top-2 right-2 flex h-3 w-3">
                    <span
                        class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-amber-500"></span>
                </span>
            </div>

            <h3 class="text-2xl font-bold text-gray-800 mb-3">Modul Kasir / Point of Sales</h3>
            <p class="text-gray-500 text-sm leading-relaxed mb-8">
                Fitur kasir utama sedang dirancang dengan antarmuka yang cepat dan responsif. Modul ini akan mempermudah
                pencatatan transaksi secara real-time, mendukung pembayaran tunai & QRIS terintegrasi, serta otomatisasi
                struk digital.
            </p>

            <div
                class="inline-flex items-center gap-3 px-5 py-3 bg-amber-50 border border-amber-200 rounded-2xl text-sm font-semibold text-amber-800 shadow-inner">
                🚧 Target Rilis: Sprint 4 — Modul POS & Integrasi Midtrans QRIS
            </div>

            <div class="mt-8">
                <a href="{{ route('dashboard') }}"
                    class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-xl shadow-sm hover:bg-gray-50 transition-colors">
                    ← Kembali ke Dashboard
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
