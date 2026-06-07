<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Warung;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->seedKategori(
            Warung::where('slug', 'warung-makan-otong')->first(),
            [
                'Makanan Berat',
                'Minuman',
                'Cemilan & Snack',
                'Sembako',
                'Peralatan Rumah Tangga',
            ]
        );

        $this->seedKategori(
            Warung::where('slug', 'warung-sembako-pak-bejo')->first(),
            [
                'Makanan Berat',
                'Minuman',
                'Sembako',
                'Bumbu Dapur',
                'Rokok & Tembakau',
            ]
        );

        $this->seedKategori(
            Warung::where('slug', 'kedai-kopi-mbak-yuni')->first(),
            [
                'Kopi & Teh',
                'Minuman Dingin',
                'Makanan Ringan',
                'Minuman Panas',
                'Paket Hemat',
            ]
        );
    }

    private function seedKategori(Warung $warung, array $daftarKategori): void
    {
        foreach ($daftarKategori as $nama) {
            Category::create([
                'warung_id'     => $warung->id,
                'nama_kategori' => $nama,
            ]);
        }
    }
}
