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
[x] Test: filter kombinasi berjalan di halaman stok
[x] Test: tambah stok manual → tercatat di riwayat
[x] Test: alert low stock muncul di dashboard dan stok/index

## Sprint 4 Checklist

[x] Routes transaksi & webhook ditambahkan ke web.php
[x] Webhook dikecualikan dari CSRF di bootstrap/app.php
[x] CommissionService dibuat
[x] TransactionService dibuat (create, settle, cancel)
[x] MidtransService dibuat (createQris, verifySignature)
[x] config/services.php diisi konfigurasi Midtrans
[ ] .env diisi MIDTRANS_SERVER_KEY & CLIENT_KEY
[x] Livewire PosKasir dibuat
[x] livewire/pos-kasir.blade.php selesai
[x] WebhookController dibuat
[x] TransaksiController dibuat
[x] pos/index.blade.php diupdate pakai @livewire('pos-kasir')
[x] transaksi/struk.blade.php selesai
[x] transaksi/riwayat.blade.php selesai
[x] Test cash: transaksi → stok berkurang → struk tampil
[ ] Test QRIS: QR muncul → polling aktif → after webhook paid → redirect struk
[ ] Test webhook: signature valid → status update → stok berkurang
[x] Test: komisi tercatat di commission_ledger setiap transaksi paid

## Sprint 5 Checklist

[x] Routes laporan ditambahkan ke web.php (4 routes)
[x] LaporanService dibuat (getSummary + parsePeriode)
[x] LaporanController dibuat (index, exportPdf, exportExcel, komisi)
[x] app/Exports/ folder dibuat
[x] LaporanExport.php (WithMultipleSheets)
[x] LaporanSummarySheet.php
[x] LaporanTransaksiSheet.php
[x] LaporanProdukSheet.php
[x] resources/views/laporan/pdf.blade.php
[x] resources/views/laporan/index.blade.php
[x] resources/views/laporan/komisi.blade.php
[x] navigation.blade.php — link Laporan ditambah
[x] DashboardController diupdate pakai LaporanService
[x] dashboard.blade.php — chart ditambah
[ ] Test: filter preset berjalan (hari ini, minggu ini, dll)
[x] Test: filter tanggal custom berjalan
[x] Test: export PDF terunduh dan isinya benar
[x] Test: export Excel terunduh 3 sheet (Ringkasan, Transaksi, Produk)
[x] Test: chart di dashboard tampil dengan data yang benar
[x] Test: laporan komisi menampilkan breakdown per transaksi

## Sprint 6 Checklist

### Testing Manual

ALUR REGISTER & SETUP:
[ ] Register akun owner baru → redirect ke /setup-warung
[ ] Isi form setup warung → redirect ke /dashboard
[ ] Login sebagai kasir → redirect ke /pos (bukan /dashboard)
[ ] Kasir coba akses /dashboard → redirect ke /pos
[ ] Owner coba akses /pos → berhasil masuk

MANAJEMEN PRODUK:
[ ] Tambah kategori baru → muncul di list
[ ] Hapus kategori yang masih punya produk → produk tetap ada (category_id null)
[ ] Tambah produk dengan foto → foto tampil di tabel
[ ] Edit produk → perubahan tersimpan
[ ] Toggle is_active produk → tidak muncul di POS
[ ] Tambah stok manual dari halaman edit → stok bertambah + riwayat tercatat
[ ] Produk dengan stok ≤ minimal → muncul alert merah di tabel dan dashboard

POS — CASH:
[ ] Buka POS → produk tampil dengan benar
[ ] Search produk → filter real-time bekerja
[ ] Toggle mode katalog → tampilan grid berubah
[ ] Tambah produk ke keranjang → qty dan subtotal benar
[ ] Tambah produk yang stoknya 0 → muncul error, tidak masuk keranjang
[ ] Kurangi qty di keranjang → benar
[ ] Hapus item dari keranjang → hilang dari list
[ ] Kosongkan keranjang → konfirmasi dan reset
[ ] Pilih Cash → klik Bayar Cash → redirect ke struk
[ ] Struk muncul dengan data benar (nama produk, harga, kasir, waktu)
[ ] Tombol cetak struk bekerja (CSS print)
[ ] Setelah transaksi: stok produk berkurang di database
[ ] Setelah transaksi: commission_ledger tercatat dengan amount 0.5%
[ ] Klik Transaksi Baru → kembali ke POS bersih

POS — QRIS:
[ ] Pilih QRIS → klik Generate QRIS → QR tampil
[ ] Polling setiap 3 detik aktif (cek di network tab browser)
[ ] Batalkan pembayaran → kembali ke mode keranjang
[ ] Simulasi webhook paid → polling deteksi → redirect ke struk

LAPORAN:
[ ] Buka laporan → default bulan ini → data benar
[ ] Ganti preset hari ini → data berubah sesuai
[ ] Custom tanggal range → filter benar
[ ] Export PDF → file terunduh, isi benar (3 section)
[ ] Export Excel → file terunduh, 3 sheet ada
[ ] Laporan komisi → total komisi sesuai dengan 0.5% × omset

STOK:
[ ] Halaman stok → summary card benar (masuk/keluar hari ini)
[ ] Alert low stock muncul jika ada produk kritis
[ ] Filter tipe IN/OUT bekerja
[ ] Filter produk bekerja
[ ] Filter rentang tanggal bekerja
[ ] Tambah stok dari /stok/tambah → stok bertambah + riwayat tercatat

KARYAWAN:
[ ] Tambah kasir → login dengan akun kasir berhasil
[ ] Hapus kasir → tidak bisa login, data transaksi lama tetap ada
[ ] Kasir tidak bisa lihat menu Produk, Stok, Laporan, Karyawan

ISOLASI DATA (KRITIS):
[ ] Register dua owner berbeda (warung A dan warung B)
[ ] Login sebagai owner warung A → tambah produk
[ ] Login sebagai owner warung B → produk warung A tidak terlihat
[ ] Pastikan laporan warung B tidak tampil data warung A

### Browser & Device Testing

[ ] Chrome desktop → semua halaman normal
[ ] Firefox desktop → semua halaman normal
[ ] Safari desktop → semua halaman normal
[ ] Chrome mobile → layout responsif
[ ] POS di tablet (landscape) → katalog mode nyaman dipakai
[ ] Struk di mobile → layout benar untuk cetak thermal

### Pre-Deployment Checklist

[ ] APP_DEBUG=false di .env production
[ ] APP_KEY sudah di-generate
[ ] Midtrans sudah switch ke production key
[ ] Database production sudah dibuat
[ ] php artisan migrate --force (jalankan di server)
[ ] php artisan storage:link (jalankan di server)
[ ] php artisan config:cache
[ ] php artisan route:cache
[ ] php artisan view:cache
[ ] npm run build (hasil dist/ di-commit atau di-build di server)
[ ] Folder storage/app/public writable (chmod 775)
[ ] .env tidak ikut di-commit ke git (.gitignore cek)

## Menambahkan user dengan role pelanggan untuk membedakan akses UI antara pelanggan dan kasir/owner dimana:

- Pelanggan hanya bisa melihat menu POS dengan fitur pembelian saja dengan metode pembayaran qris
- Kasir/Owner bisa melihat menu POS dengan fitur pembelian dan stok produk dan metode pembayaran cash dan qris
