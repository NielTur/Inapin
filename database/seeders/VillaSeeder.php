<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VillaSeeder extends Seeder
{
    public function run(): void
    {

        $villas = [
            [
                'nama_villa' => 'Villa Bukit Hijau',
                'deskripsi' => 'Villa mewah dengan pemandangan bukit hijau yang memukau, cocok untuk keluarga besar.',
                'kota' => 'Bogor',
                'harga' => 1500000,
                'kapasitas' => 10,
                'jumlah_kamar' => 4,
                'jumlah_kamar_mandi' => 3,
                'status' => 'disetujui',
                'ulasan' => 4.8,
                'whatsapp' => '081234567890',
                'instagram' => '@villabukit_puncak',
                'facebook' => 'villabukit.puncak',
                'tiktok' => '@villabukit_official',
                'alamat' => 'Jl. Raya Puncak No. 12, Bogor, Jawa Barat',
            ],
            [
                'nama_villa' => 'Villa Sawah Jogja',
                'deskripsi' => 'Suasana pedesaan Jawa yang autentik dengan hamparan sawah hijau nan menenangkan.',
                'kota' => 'Yogyakarta',
                'harga' => 900000,
                'kapasitas' => 8,
                'jumlah_kamar' => 4,
                'jumlah_kamar_mandi' => 2,
                'status' => 'disetujui',
                'ulasan' => 4.6,
                'whatsapp' => '084567890123',
                'instagram' => '@villasawah_jogja',
                'facebook' => 'villasawahjogja',
                'tiktok' => null,
                'alamat' => 'Jl. Parangtritis KM 14, Bantul, Yogyakarta',
            ],
            [
                'nama_villa' => 'Villa Lembang Sejuk',
                'deskripsi' => 'Villa nyaman dengan udara sejuk khas dataran tinggi Lembang dan kolam renang air hangat.',
                'kota' => 'Bandung',
                'harga' => 1800000,
                'kapasitas' => 12,
                'jumlah_kamar' => 5,
                'jumlah_kamar_mandi' => 4,
                'status' => 'disetujui',
                'ulasan' => 4.5,
                'whatsapp' => '085678901234',
                'instagram' => '@villalembang_official',
                'facebook' => 'villalembang',
                'tiktok' => '@villalembang_sejuk',
                'alamat' => 'Jl. Lembang Indah No. 45, Lembang, Bandung Barat',
            ],

        ];

        foreach ($villas as $villaData) {
            $id = DB::table('villa')->insertGetId(array_merge($villaData, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));

            $fasilitasList = [
                'Kolam Renang',
                'WiFi Gratis',
                'Sarapan',
                'Parkir Luas',
                'AC Setiap Kamar',
            ];

            foreach ($fasilitasList as $fasilitas) {
                DB::table('fasilitas_villa')->insert([
                    'id_villa' => $id,
                    'fasilitas' => $fasilitas,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        $this->command->info('VillaSeeder: Berhasil');
    }
}
