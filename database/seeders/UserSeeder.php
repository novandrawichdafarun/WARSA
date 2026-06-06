<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Warung;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin WARSA',
            'email' => 'WARSA@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
            'warung_id' => 1,
        ]);

        User::create([
            'name' => 'Sari Pemilik',
            'email' => 'owner@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'owner',
            'warung_id' => 2,
        ]);

        User::create([
            'name' => 'Budi Kasir',
            'email' => 'kasir@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'kasir',
            'warung_id' => 2,
        ]);
    }
}
