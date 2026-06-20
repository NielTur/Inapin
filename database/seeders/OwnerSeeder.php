<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class OwnerSeeder extends Seeder
{
    public function run(): void
    {
        $owners = [
            [
                'nama' => 'Daniel',
                'email' => 'daniel@gmail.com',
                'password' => Hash::make('12345678'),
                'phone' => '081234567001',
                'alamat' => 'Jl. Sudirman No. 1, Jakarta Selatan',
                'nik' => '3171010101900001',
                'tanggal_lahir' => '1990-01-01',
            ],
            [
                'nama' => 'Khoir',
                'email' => 'khoir@gmail.com',
                'password' => Hash::make('12345678'),
                'phone' => '081234567002',
                'alamat' => 'Jl. Merdeka No. 2, Bandung',
                'nik' => '3273010101910002',
                'tanggal_lahir' => '1991-02-02',
            ],
            [
                'nama' => 'Shabil',
                'email' => 'shabil@gmail.com',
                'password' => Hash::make('12345678'),
                'phone' => '081234567003',
                'alamat' => 'Jl. Pajajaran No. 3, Bogor',
                'nik' => '3271010101920003',
                'tanggal_lahir' => '1992-03-03',
            ],
            [
                'nama' => 'Furqon',
                'email' => 'furqon@gmail.com',
                'password' => Hash::make('12345678'),
                'phone' => '081234567004',
                'alamat' => 'Jl. Malioboro No. 4, Yogyakarta',
                'nik' => '3471010101930004',
                'tanggal_lahir' => '1993-04-04',
            ],
            [
                'nama' => 'Nabila',
                'email' => 'nabila@gmail.com',
                'password' => Hash::make('12345678'),
                'phone' => '081234567005',
                'alamat' => 'Jl. Ahmad Yani No. 5, Bekasi',
                'nik' => '3275010101940005',
                'tanggal_lahir' => '1994-05-05',
            ],
        ];

        foreach ($owners as $ownerData) {
            DB::table('owner')->insert(array_merge($ownerData, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        $this->command->info('OwnerSeeders: Berhasil dibuat');
    }
}
