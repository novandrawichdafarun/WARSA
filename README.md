<p align="center"><img src="./docs/logo.png" width="400" alt="WARSA Logo"></p>

## About WARSA (Warung Smart Aplication)

WARSA adalah aplikasi manajemen warung berbasis Laravel. Dokumentasi ini menjelaskan instalasi, konfigurasi, arsitektur, perintah penting, deploy, testing, dan panduan kontribusi. Sesuaikan bagian "Fitur" dan variabel .env sesuai kebutuhan proyek Anda.

---

## Fitur

- Otentikasi pengguna (login/register, reset password)
- Manajemen produk & kategori
- Manajemen stok & supplier
- Manajemen Karyawan Kasir (Owner)
- Transaksi / Penjualan (Cash, Qris)
- Laporan penjualan & stok
- Role & permission
- API dasar untuk integrasi
- Manajemen User & Warung (Super Admin)

---

## Prasyarat

- Windows (Laragon direkomendasikan) atau Linux
- PHP 8.2+ (sesuaikan dengan versi Laravel)
- Composer
- Node.js 16+ / npm atau yarn
- MySQL / MariaDB / PostgreSQL
- (Opsional) Redis untuk cache/queue

---

## Instalasi (Lokal - Laragon / Windows)

1. Clone repository:
    ```bash
    git clone <repo-url> d:\laragon\www\WARSA
    cd d:\laragon\www\WARSA
    ```
2. Install dependensi PHP:
    ```bash
    composer install
    ```
3. Salin file lingkungan dan buat app key:
    - CMD: `copy .env.example .env`
    - PowerShell/Git Bash: `cp .env.example .env`
    ```bash
    php artisan key:generate
    ```
4. Sesuaikan `.env` (DB, MAIL, APP_URL, dsb).
5. Migrasi dan seeding:
    ```bash
    php artisan migrate --seed
    ```
6. Link storage:
    ```bash
    php artisan storage:link
    ```
7. Build aset frontend:
    ```bash
    npm install
    npm run dev   # atau npm run build untuk produksi
    ```
8. Jalankan aplikasi:
    - Laravel dev server:
        ```bash
        composer run dev
        ```
    - Atau gunakan virtual host Laragon (rekomendasi untuk Windows).

---

## Struktur Folder Proyek

```
d:\laragon\www\WARSA
├── app/                     # Core app: Models, Controllers, Business logic
│   ├── Console              # Artisan custom commands
│   ├── Exceptions           # Exception handlers
│   ├── Http
│   │   ├── Controllers      # HTTP & API controllers
│   │   ├── Middleware       # Request middleware
│   │   ├── Requests         # Form Request validation classes
│   │   └── Resources        # API Resources / Transformers
│   ├── Models               # Eloquent models
│   ├── Policies             # Authorization policies
│   ├── Providers            # Service providers (bootstrapping)
│   └── Services             # Business/service classes (domain logic)
├── bootstrap/               # Framework bootstrap files & cache
│   └── cache
├── config/                  # Configuration files (app, database, mail, queue, dll.)
├── database/
│   ├── factories            # Model factories (testing/seeding)
│   ├── migrations           # Database schema migrations
│   └── seeders              # Seeders untuk data awal / dummy
├── public/                  # Web root: index.php, compiled assets, symlink uploads
│   ├── index.php
│   └── storage -> ../storage/app/public
├── resources/               # Views, translations, frontend source (js/css)
│   ├── views
│   ├── lang
│   ├── js
│   └── css
├── routes/                  # Route definitions (web.php, api.php, channels.php, console.php)
│   ├── web.php
│   ├── api.php
│   ├── channels.php
│   └── console.php
├── storage/                 # Runtime files: logs, cache, sessions, uploads (jangan commit)
│   ├── app
│   ├── framework
│   │   ├── cache
│   │   ├── sessions
│   │   └── views
│   └── logs
├── tests/                   # Automated tests (Feature, Unit)
│   ├── Feature
│   └── Unit
├── vendor/                  # Composer dependencies (auto-generated)
├── .env                     # Environment variables (lokal; jangan commit)
├── .env.example             # Contoh file environment
├── artisan                  # Laravel Artisan CLI entrypoint
├── composer.json            # PHP dependencies & scripts
├── package.json             # Node dependencies & scripts
├── vite.config.js (atau webpack.mix.js) # Frontend build config
├── phpunit.xml              # PHPUnit configuration
└── README.md                # Dokumentasi proyek
```

## Contoh .env (sesuaikan)

```env
APP_NAME=WARSA
APP_ENV=local
APP_KEY=
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=WARSA
DB_USERNAME=root
DB_PASSWORD=

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_FROM_ADDRESS=no-reply@WARSA.test
```

---

## Database & Seeder

- Jalankan migrasi: `php artisan migrate`
- Jika butuh reset: `php artisan migrate:fresh --seed`
- Buat seeder & factory untuk data uji di `database/seeders` dan `database/factories`.

---

## Perintah Penting

- Jalankan test: `php artisan test`
- Daftar rute: `php artisan route:list`
- Cache & optimasi:
    ```bash
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    ```
- Clear cache:
    ```bash
    php artisan config:clear
    php artisan cache:clear
    php artisan route:clear
    ```
- Storage link: `php artisan storage:link`
- Queue worker: `php artisan queue:work`
- Schedule (prod): jalankan `* * * * * php /path/to/artisan schedule:run >> /dev/null 2>&1` (Linux) atau gunakan Task Scheduler pada Windows.

---

## Testing & Quality

- Unit/Feature tests: `php artisan test`
- Linting / static analysis: integrasikan tools seperti PHP-CS-Fixer, PHPCS, PHPStan, Pest (opsional).
- Jalankan CI untuk menjalankan test otomatis sebelum merge.

---

## Arsitektur & Struktur Folder (ringkas)

- app/ — inti aplikasi (Models, Http/Controllers, Policies, Services)
- config/ — konfigurasi
- database/migrations, seeders, factories
- resources/views — blade templates
- resources/js / resources/css — aset frontend
- routes/ — web.php, api.php
- storage/ — logs, cache, uploads
- tests/ — unit dan feature tests

---

## API & Dokumentasi Rute

- Gunakan `php artisan route:list` untuk melihat endpoint.
- Untuk dokumentasi API otomatis, gunakan paket seperti Scribe atau knuckleswtf/laravel-api-docs.
- Pastikan menambahkan middleware auth dan versi API jika perlu.

---

## Troubleshooting Umum

- Error koneksi DB: periksa `DB_HOST`, `DB_PORT`, kredensial.
- Permission storage: `chown -R www-data:www-data storage bootstrap/cache` (Linux).
- Composer out of memory: `COMPOSER_MEMORY_LIMIT=-1 composer install`
- Route 404 setelah deploy: jalankan `php artisan route:cache` ulang jika rute berubah.

---

## Kontribusi

- Fork → branch fitur → PR ke main/master.
- Ikuti standar kode (PSR-12), sertakan test untuk fitur baru.
- Tulis deskripsi PR jelas dan langkah testing.

---

## Tempat Menyimpan Dokumentasi Lebih Lanjut

- Buat folder `docs/` untuk dokumentasi modul (contoh: API, ERD, alur transaksi).
- Pertimbangkan menggunakan MkDocs / GitHub Pages untuk dokumentasi publik.

---

## Kontak & Maintainer

- Isi detail maintainer atau tim di sini: (nama, email, slack/discord).

---

## Lisensi

Proyek ini memakai lisensi [MIT license](https://opensource.org/licenses/MIT). — sesuaikan jika perlu.

---

## Perintah ringkas (cheatsheet)

```bash
composer install
copy .env.example .env   # Windows
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
npm install && npm run dev
php artisan serve
php artisan test
```

---

Dokumentasi ini adalah template lengkap untuk proyek Laravel "WARSA". Lengkapi bagian fitur, endpoint, dan kontak sesuai kondisi nyata proyek. Jika ingin, saya bisa menghasilkan dokumentasi API otomatis (Scribe) atau berkas .env.example terperinci setelah Anda kirimkan struktur model/rute utama.
