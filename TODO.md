## Sprint 1 Checklist

[x] Laravel 13 terinstall
[x] Semua package terinstall (Breeze, Livewire, dll)
[x] .env terkonfigurasi
[x] Semua migration berjalan tanpa error
[x] Semua model dibuat dengan relasi yang benar
[x] Global Scope warung_id terpasang
[x] Middleware tiga role terdaftar
[x] Alur register → setup warung → dashboard berjalan
[x] Seeder berjalan, bisa login sebagai owner & kasir
[x] Route terdefinisi dengan middleware yang tepat
[x] php artisan test tidak ada error

## Sprint 2 Checklist

[x] Route produk & kategori ditambahkan ke web.php
[x] 4 Form Request dibuat dan diisi
[x] StockService dibuat di app/Services/
[x] CategoryController selesai (index, store, destroy)
[x] ProdukController selesai (full CRUD + tambahStok)
[x] Livewire ProdukTable dibuat
[x] View: produk/index, create, edit
[x] View: kategori/index
[x] Upload foto berjalan (php artisan storage:link sudah dijalankan)
[x] Search real-time Livewire berfungsi
[x] Filter kategori & status berfungsi
[x] Alert stok rendah tampil merah di tabel
[x] Riwayat stok muncul di halaman edit

## Sprint 3 Checklist

[x] Route /stok dan /stok/tambah ditambahkan ke web.php
[x] StokController dibuat (index, create, store)
[x] TambahStokRequest diupdate — tambah product_id rule
[x] Livewire StokTable dibuat dengan 4 filter
[x] stok/index.blade.php — summary card + alert + tabel
[x] stok/create.blade.php — form + JS info stok
[x] livewire/stok-table.blade.php selesai
[x] navigation.blade.php — link Stok ditambah
[x] DashboardController — tambah produk_low_stock_list
[x] dashboard.blade.php — tampilkan alert low stock
[ ] Test: filter kombinasi berjalan di halaman stok
[ ] Test: tambah stok manual → tercatat di riwayat
[ ] Test: alert low stock muncul di dashboard dan stok/index