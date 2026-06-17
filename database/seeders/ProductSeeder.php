<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Warung;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->seedWarungMakanOtong();
        $this->seedWarungPakBejo();
        $this->seedKedaiKopiMbakYuni();
    }

    private function seedWarungMakanOtong(): void
    {
        $warung = Warung::where('slug', 'warung-makan-otong')->first();
        $cat = $this->getKategori($warung->id);

        $produk = [
            'Makanan Berat' => [
                ['nama_produk' => 'Nasi Goreng Biasa', 'harga_jual' => 15000, 'harga_beli' => 8000, 'stok' => 200],
                ['nama_produk' => 'Nasi Goreng Spesial', 'harga_jual' => 20000, 'harga_beli' => 12000, 'stok' => 200],
                ['nama_produk' => 'Mie Ayam Bakso', 'harga_jual' => 15000, 'harga_beli' => 8000, 'stok' => 200],
                ['nama_produk' => 'Bakso Urat Kuah', 'harga_jual' => 18000, 'harga_beli' => 10000, 'stok' => 200],
                ['nama_produk' => 'Pecel Lele + Nasi', 'harga_jual' => 22000, 'harga_beli' => 13000, 'stok' => 200],
            ],
            'Minuman' => [
                ['nama_produk' => 'Es Teh Manis', 'harga_jual' => 5000, 'harga_beli' => 2000, 'stok' => 300],
                ['nama_produk' => 'Kopi Hitam Tubruk', 'harga_jual' => 6000, 'harga_beli' => 2500, 'stok' => 300],
                ['nama_produk' => 'Es Jeruk Peras', 'harga_jual' => 7000, 'harga_beli' => 3000, 'stok' => 300],
                ['nama_produk' => 'Air Mineral Aqua 600ml', 'harga_jual' => 4000, 'harga_beli' => 2500, 'stok' => 300],
                ['nama_produk' => 'Jus Alpukat', 'harga_jual' => 15000, 'harga_beli' => 8000, 'stok' => 200],
            ],
            'Cemilan & Snack' => [
                ['nama_produk' => 'Chitato Original 68gr', 'harga_jual' => 12000, 'harga_beli' => 9500, 'stok' => 150],
                ['nama_produk' => 'Oreo Original', 'harga_jual' => 5000, 'harga_beli' => 3500, 'stok' => 200],
                ['nama_produk' => 'Indomie Goreng', 'harga_jual' => 4000, 'harga_beli' => 2800, 'stok' => 250],
            ],
            'Sembako' => [
                ['nama_produk' => 'Beras Premium 5kg', 'harga_jual' => 78000, 'harga_beli' => 70000, 'stok' => 80, 'stok_minimal' => 10],
                ['nama_produk' => 'Minyak Goreng Filma 1L', 'harga_jual' => 19000, 'harga_beli' => 15500, 'stok' => 80, 'stok_minimal' => 10],
                ['nama_produk' => 'Gula Pasir 1kg', 'harga_jual' => 16000, 'harga_beli' => 14000, 'stok' => 80, 'stok_minimal' => 10],
            ],
            'Peralatan Rumah Tangga' => [
                ['nama_produk' => 'Sabun Cuci Piring Sunlight', 'harga_jual' => 8000, 'harga_beli' => 5500, 'stok' => 50],
                ['nama_produk' => 'Kantong Plastik 1/2 kg', 'harga_jual' => 2000, 'harga_beli' => 1200, 'stok' => 100],
                ['nama_produk' => 'Gudang Garam Merah', 'harga_jual' => 25000, 'harga_beli' => 23000, 'stok' => 4, 'stok_minimal' => 15],
                ['nama_produk' => 'Sampoerna Mild 16', 'harga_jual' => 29000, 'harga_beli' => 26500, 'stok' => 3, 'stok_minimal' => 15],
            ],
        ];

        $this->bulkCreate($warung->id, $cat, $produk);
    }

    private function seedWarungPakBejo(): void
    {
        $warung = Warung::where('slug', 'warung-sembako-pak-bejo')->first();
        $cat = $this->getKategori($warung->id);

        $produk = [
            'Makanan Berat' => [
                ['nama_produk' => 'Nasi Pecel Komplit', 'harga_jual' => 12000, 'harga_beli' => 6500, 'stok' => 200],
                ['nama_produk' => 'Sayur Lodeh + Nasi', 'harga_jual' => 10000, 'harga_beli' => 5000, 'stok' => 200],
                ['nama_produk' => 'Tempe Goreng (2 pcs)', 'harga_jual' => 3000, 'harga_beli' => 1500, 'stok' => 300],
                ['nama_produk' => 'Tahu Goreng (2 pcs)', 'harga_jual' => 3000, 'harga_beli' => 1500, 'stok' => 300],
                ['nama_produk' => 'Nasi Rawon', 'harga_jual' => 20000, 'harga_beli' => 12000, 'stok' => 200],
            ],
            'Minuman' => [
                ['nama_produk' => 'Es Teh Manis', 'harga_jual' => 4000, 'harga_beli' => 1500, 'stok' => 300],
                ['nama_produk' => 'Air Aqua 600ml', 'harga_jual' => 4000, 'harga_beli' => 2500, 'stok' => 300],
                ['nama_produk' => 'Kopi Sachet Kapal Api', 'harga_jual' => 3000, 'harga_beli' => 1500, 'stok' => 200],
            ],
            'Sembako' => [
                ['nama_produk' => 'Beras Ramos 5kg', 'harga_jual' => 72000, 'harga_beli' => 64000, 'stok' => 80, 'stok_minimal' => 10],
                ['nama_produk' => 'Telur Ayam 1kg', 'harga_jual' => 28000, 'harga_beli' => 24000, 'stok' => 80, 'stok_minimal' => 10],
                ['nama_produk' => 'Tepung Terigu Segitiga 1kg', 'harga_jual' => 14000, 'harga_beli' => 11000, 'stok' => 80, 'stok_minimal' => 10],
                ['nama_produk' => 'Minyak Bimoli 1L', 'harga_jual' => 19000, 'harga_beli' => 15500, 'stok' => 80, 'stok_minimal' => 10],
            ],
            'Bumbu Dapur' => [
                ['nama_produk' => 'Bawang Merah 250gr', 'harga_jual' => 8000, 'harga_beli' => 5000, 'stok' => 100],
                ['nama_produk' => 'Bawang Putih 250gr', 'harga_jual' => 6000, 'harga_beli' => 4000, 'stok' => 100],
                ['nama_produk' => 'Cabe Merah 100gr', 'harga_jual' => 5000, 'harga_beli' => 3000, 'stok' => 80],
                ['nama_produk' => 'Gula Jawa 250gr', 'harga_jual' => 5000, 'harga_beli' => 3500, 'stok' => 80],
            ],
            'Rokok & Tembakau' => [
                ['nama_produk' => 'Gudang Garam Merah', 'harga_jual' => 25000, 'harga_beli' => 23000, 'stok' => 100, 'stok_minimal' => 20],
                ['nama_produk' => 'Djarum Super', 'harga_jual' => 25000, 'harga_beli' => 23000, 'stok' => 80, 'stok_minimal' => 20],
                ['nama_produk' => 'Class Mild 16', 'harga_jual' => 22000, 'harga_beli' => 20000, 'stok' => 5, 'stok_minimal' => 15],
                ['nama_produk' => 'Lucky Strike', 'harga_jual' => 31000, 'harga_beli' => 28500, 'stok' => 4, 'stok_minimal' => 10],
            ],
        ];

        $this->bulkCreate($warung->id, $cat, $produk);
    }

    private function seedKedaiKopiMbakYuni(): void
    {
        $warung = Warung::where('slug', 'kedai-kopi-mbak-yuni')->first();
        $cat = $this->getKategori($warung->id);

        $produk = [
            'Kopi & Teh' => [
                ['nama_produk' => 'Kopi Hitam Robusta', 'harga_jual' => 8000, 'harga_beli' => 3000, 'stok' => 300],
                ['nama_produk' => 'Kopi Susu Segar', 'harga_jual' => 12000, 'harga_beli' => 5000, 'stok' => 300],
                ['nama_produk' => 'Es Kopi Susu Kekinian', 'harga_jual' => 18000, 'harga_beli' => 7000, 'stok' => 300],
                ['nama_produk' => 'Matcha Latte', 'harga_jual' => 20000, 'harga_beli' => 8000, 'stok' => 200],
                ['nama_produk' => 'Teh Tarik Spesial', 'harga_jual' => 12000, 'harga_beli' => 4500, 'stok' => 200],
            ],
            'Minuman Dingin' => [
                ['nama_produk' => 'Es Jeruk Nipis', 'harga_jual' => 8000, 'harga_beli' => 3000, 'stok' => 200],
                ['nama_produk' => 'Es Alpukat Cokelat', 'harga_jual' => 18000, 'harga_beli' => 8000, 'stok' => 200],
                ['nama_produk' => 'Jus Mangga Segar', 'harga_jual' => 15000, 'harga_beli' => 6000, 'stok' => 200],
                ['nama_produk' => 'Soda Gembira', 'harga_jual' => 10000, 'harga_beli' => 4000, 'stok' => 150],
            ],
            'Makanan Ringan' => [
                ['nama_produk' => 'Roti Bakar Srikaya', 'harga_jual' => 12000, 'harga_beli' => 5500, 'stok' => 150],
                ['nama_produk' => 'Roti Bakar Cokelat Keju', 'harga_jual' => 14000, 'harga_beli' => 6500, 'stok' => 150],
                ['nama_produk' => 'Pisang Goreng Crispy', 'harga_jual' => 10000, 'harga_beli' => 4000, 'stok' => 100],
                ['nama_produk' => 'Cireng Rujak', 'harga_jual' => 8000, 'harga_beli' => 3000, 'stok' => 100],
            ],
            'Minuman Panas' => [
                ['nama_produk' => 'Kopi Jahe Hangat', 'harga_jual' => 10000, 'harga_beli' => 4000, 'stok' => 200],
                ['nama_produk' => 'Susu Jahe', 'harga_jual' => 10000, 'harga_beli' => 4000, 'stok' => 200],
                ['nama_produk' => 'Coklat Hangat', 'harga_jual' => 12000, 'harga_beli' => 5000, 'stok' => 150],
            ],
            'Paket Hemat' => [
                ['nama_produk' => 'Paket Kopi + Roti', 'harga_jual' => 22000, 'harga_beli' => 10000, 'stok' => 100],
                ['nama_produk' => 'Paket Es Kopi + Snack', 'harga_jual' => 25000, 'harga_beli' => 11000, 'stok' => 100],
                ['nama_produk' => 'Wedang Uwuh Rempah', 'harga_jual' => 12000, 'harga_beli' => 5000, 'stok' => 6, 'stok_minimal' => 15],
                ['nama_produk' => 'Brown Sugar Milk Tea', 'harga_jual' => 20000, 'harga_beli' => 8000, 'stok' => 5, 'stok_minimal' => 10],
            ],
        ];

        $this->bulkCreate($warung->id, $cat, $produk);
    }

    private function getKategori(int $warungId): Collection
    {
        return Category::where('warung_id', $warungId)
            ->get()
            ->keyBy('nama_kategori');
    }

    private function getRealImageUrl(string $namaProduk, string $kategori): string
    {
        $nama = strtolower($namaProduk);

        if (str_contains($nama, 'nasi goreng'))
            return 'https://images.unsplash.com/photo-1604908176997-125f25cc6f3d?auto=format&fit=crop&w=400&q=80';
        if (str_contains($nama, 'mie') || str_contains($nama, 'indomie'))
            return 'https://images.unsplash.com/photo-1569718212165-3a8278d5f624?auto=format&fit=crop&w=400&q=80';
        if (str_contains($nama, 'bakso') || str_contains($nama, 'rawon') || str_contains($nama, 'lodeh'))
            return 'https://images.unsplash.com/photo-1594998893017-36147cbcae05?auto=format&fit=crop&w=400&q=80';
        if (str_contains($nama, 'pecel') || str_contains($nama, 'lele') || str_contains($nama, 'tempe') || str_contains($nama, 'tahu'))
            return 'https://images.unsplash.com/photo-1621852004158-f3bc188ace2d?auto=format&fit=crop&w=400&q=80';

        if (str_contains($nama, 'kopi') || str_contains($nama, 'latte'))
            return 'https://images.unsplash.com/photo-1509042239860-f550ce710b93?auto=format&fit=crop&w=400&q=80';
        if (str_contains($nama, 'teh'))
            return 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?auto=format&fit=crop&w=400&q=80';
        if (str_contains($nama, 'jeruk') || str_contains($nama, 'jus') || str_contains($nama, 'alpukat') || str_contains($nama, 'soda') || str_contains($nama, 'matcha'))
            return 'https://images.unsplash.com/photo-1600271886742-f049cd451bba?auto=format&fit=crop&w=400&q=80';
        if (str_contains($nama, 'air') || str_contains($nama, 'aqua'))
            return 'https://images.unsplash.com/photo-1548839140-29a749e1bc4e?auto=format&fit=crop&w=400&q=80';
        if (str_contains($nama, 'susu') || str_contains($nama, 'coklat'))
            return 'https://images.unsplash.com/photo-1570197781417-063fbdd5743c?auto=format&fit=crop&w=400&q=80';

        if (str_contains($nama, 'beras'))
            return 'https://images.unsplash.com/photo-1586201375761-83865001e8ac?auto=format&fit=crop&w=400&q=80';
        if (str_contains($nama, 'telur'))
            return 'https://images.unsplash.com/photo-1587486913049-53fc88980cfc?auto=format&fit=crop&w=400&q=80';
        if (str_contains($nama, 'minyak') || str_contains($nama, 'gula') || str_contains($nama, 'tepung'))
            return 'https://images.unsplash.com/photo-1626806787426-5910811b6325?auto=format&fit=crop&w=400&q=80';
        if (str_contains($nama, 'bawang') || str_contains($nama, 'cabe'))
            return 'https://images.unsplash.com/photo-1596040033229-a9821ebd058d?auto=format&fit=crop&w=400&q=80';

        if (str_contains($nama, 'roti') || str_contains($nama, 'pisang') || str_contains($nama, 'cireng') || str_contains($nama, 'chitato') || str_contains($nama, 'oreo'))
            return 'https://images.unsplash.com/photo-1621939514649-280e2ee25f60?auto=format&fit=crop&w=400&q=80';

        if (str_contains($nama, 'rokok') || str_contains($nama, 'garam') || str_contains($nama, 'sampoerna') || str_contains($nama, 'djarum') || str_contains($nama, 'mild') || str_contains($nama, 'strike'))
            return 'https://images.unsplash.com/photo-1528321917418-40bcce6f05bf?auto=format&fit=crop&w=400&q=80';
        if (str_contains($nama, 'sabun') || str_contains($nama, 'plastik'))
            return 'https://images.unsplash.com/photo-1584824486509-114594d65b7a?auto=format&fit=crop&w=400&q=80';

        if (str_contains(strtolower($kategori), 'makanan'))
            return 'https://images.unsplash.com/photo-1543826173-70651703c5a4?auto=format&fit=crop&w=400&q=80';
        if (str_contains(strtolower($kategori), 'minuman'))
            return 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?auto=format&fit=crop&w=400&q=80';

        // Default jika tidak ada yang cocok
        return 'https://images.unsplash.com/photo-1580828369062-cb0f074d2845?auto=format&fit=crop&w=400&q=80';
    }

    private function bulkCreate(int $warungId, Collection $cat, array $produk): void
    {
        if (!Storage::disk('public')->exists('produk')) {
            Storage::disk('public')->makeDirectory('produk');
        }
        foreach ($produk as $namaKategori => $items) {
            $category = $cat->get($namaKategori);

            foreach ($items as $item) {
                $namaFileGambar = Str::slug($item['nama_produk']) . '.jpg';
                $pathLokal = 'produk/' . $namaFileGambar;

                if (!Storage::disk('public')->exists($pathLokal)) {
                    $imageUrl = $this->getRealImageUrl($item['nama_produk'], $category?->nama_kategori ?? '');

                    try {
                        $response = Http::timeout(10)->get($imageUrl);

                        if ($response->successful()) {
                            Storage::disk('public')->put($pathLokal, $response->body());
                            $this->command->line("  ✓ Terunduh: " . $item['nama_produk']);
                        }
                    } catch (\Exception $e) {
                        $this->command->error("  ✗ Gagal unduh foto: " . $item['nama_produk']);
                    }
                }

                Product::create([
                    'warung_id' => $warungId,
                    'category_id' => $category?->id,
                    'nama_produk' => $item['nama_produk'],
                    'harga_jual' => $item['harga_jual'],
                    'harga_beli' => $item['harga_beli'],
                    'stok' => $item['stok'],
                    'stok_minimal' => $item['stok_minimal'] ?? 5,
                    'is_active' => true,
                    'foto' => Storage::disk('public')->exists($pathLokal) ? $pathLokal : null,
                ]);
            }
        }
    }
}
