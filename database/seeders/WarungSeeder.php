<?php

namespace Database\Seeders;

use App\Models\Warung;
use Illuminate\Database\Seeder;

class WarungSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Warung::create([
            'nama_warung' => 'Pemilik WARSA',
            'slug' => 'warsa',
            'alamat' => 'Jl. Jambangan I No.12/A',
            'telepon' => '0882009633169',
            'logo' => null,
            'is_active' => true,
        ]);

        Warung::create([
            'nama_warung' => 'Warung Makan Otong',
            'slug' => 'warung-makan-otong',
            'alamat' => 'Jl. Raya Genteng No. 12, Surabaya',
            'telepon' => '081234567890',
            'logo' => null,
            'is_active' => true,
        ]);

        Warung::create([
            'nama_warung' => 'Warung Sembako Pak Bejo',
            'slug' => 'warung-sembako-pak-bejo',
            'alamat' => 'Jl. Ngagel Rejo No. 17, Surabaya',
            'telepon' => '085678901234',
            'logo' => null,
            'is_active' => true,
        ]);

        Warung::create([
            'nama_warung' => 'Kedai Kopi Mbak Yuni',
            'slug' => 'kedai-kopi-mbak-yuni',
            'alamat' => 'Jl. Tunjungan No. 8, Surabaya',
            'telepon' => '087890123456',
            'logo' => null,
            'is_active' => true,
        ]);
    }
}
