<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('customer')->insert([
            'nama' => 'user',
            'email' => 'user@gmail.com',
            'password' => Hash::make('12345678'),
            'phone' => '08888888881',
            'tanggal_lahir' => '2000-10-09',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->command->info('CustomerSeeder: Berhasil dibuat');
    }
}
