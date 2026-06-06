<?php

namespace Database\Seeders;

use App\Models\Warung;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
            'slug' => 'WARSA',
            'alamat' => 'Jl. Jambangan I No.12/A',
            'telepon' => '0882009633169',
            'logo' => null,
            'is_active' => true,
        ]);

        Warung::create([
            'nama_warung' => 'Warung Bu Sari',
            'slug' => 'warung-bu-sari',
            'alamat' => 'Jl. Raya Genteng No. 12, Surabaya',
            'telepon' => '081234567890',
            'logo' => null,
            'is_active' => true,
        ]);
    }
}
