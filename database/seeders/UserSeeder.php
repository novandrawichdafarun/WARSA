<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Warung;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $warungAdmin = Warung::where('slug', 'warsa')->first();
        $WarungOtong = Warung::where('slug', 'warung-makan-otong')->first();
        $warungPakBejo = Warung::where('slug', 'warung-sembako-pak-bejo')->first();
        $warungMbakYuni = Warung::where('slug', 'kedai-kopi-mbak-yuni')->first();

        User::create([
            'name' => 'Admin WARSA',
            'email' => 'WARSA@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
            'warung_id' => $warungAdmin->id,
        ]);

        User::create([
            'name' => 'Otong surotong',
            'email' => 'otong@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'owner',
            'warung_id' => $WarungOtong->id,
        ]);

        User::create([
            'name' => 'Ucup Surucup',
            'email' => 'ucup@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'kasir',
            'warung_id' => $WarungOtong->id,
        ]);

        User::create([
            'name' => 'Bejo Prasetyo',
            'email' => 'bejo@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'owner',
            'warung_id' => $warungPakBejo->id,
        ]);

        User::create([
            'name' => 'Agus Wibowo',
            'email' => 'agus@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'kasir',
            'warung_id' => $warungPakBejo->id,
        ]);

        User::create([
            'name' => 'Yuni Rahayu',
            'email' => 'yuni@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'owner',
            'warung_id' => $warungMbakYuni->id,
        ]);

        User::create([
            'name' => 'Tono Wijaya',
            'email' => 'tono@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'kasir',
            'warung_id' => $warungMbakYuni->id,
        ]);
    }
}
