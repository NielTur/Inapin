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
                'nama_villa'  => 'Villa Bukit Hijau',
                'deskripsi'   => 'Villa mewah dengan pemandangan bukit hijau yang memukau, cocok untuk keluarga besar.',
                'kota'        => 'Puncak',
                'harga'       => 1500000,
                'kapasitas'   => 10,
                'status'      => 'aktif',
                'ulasan'      => 4.8,
                'whatsapp'  => '081234567890',
                'instagram' => '@villabukit_puncak',
                'facebook'  => 'villabukit.puncak',
                'tiktok'    => '@villabukit_official',
                'alamat'      => 'Jl. Raya Puncak No. 12, Bogor, Jawa Barat',
            ],
            [
                'nama_villa'  => 'Villa Pantai Biru',
                'deskripsi'   => 'Villa tepi pantai dengan akses langsung ke pantai pasir putih yang bersih.',
                'kota'        => 'Bali',
                'harga'       => 2500000,
                'kapasitas'   => 8,
                'status'      => 'aktif',
                'ulasan'      => 4.9,
                'whatsapp'  => '082345678901',
                'instagram' => '@villapantaibiru_bali',
                'facebook'  => 'villapantaibiru',
                'tiktok'    => null,
                'alamat'      => 'Jl. Pantai Seminyak No. 5, Badung, Bali',
            ],
            [
                'nama_villa'  => 'Villa Hutan Tropis',
                'deskripsi'   => 'Rasakan sensasi menginap di tengah hutan tropis yang sejuk dan asri.',
                'kota'        => 'Lombok',
                'harga'       => 1200000,
                'kapasitas'   => 6,
                'status'      => 'aktif',
                'ulasan'      => 4.7,
                'whatsapp'  => '083456789012',
                'instagram' => '@villahumantropis',
                'facebook'  => null,
                'tiktok'    => '@villahumantropis',
                'alamat'      => 'Jl. Senggigi Raya No. 88, Lombok Barat, NTB',
            ],
            [
                'nama_villa'  => 'Villa Sawah Jogja',
                'deskripsi'   => 'Suasana pedesaan Jawa yang autentik dengan hamparan sawah hijau nan menenangkan.',
                'kota'        => 'Yogyakarta',
                'harga'       => 900000,
                'kapasitas'   => 8,
                'status'      => 'aktif',
                'ulasan'      => 4.6,
                'whatsapp'  => '084567890123',
                'instagram' => '@villasawah_jogja',
                'facebook'  => 'villasawahjogja',
                'tiktok'    => null,
                'alamat'      => 'Jl. Parangtritis KM 14, Bantul, Yogyakarta',
            ],
            [
                'nama_villa'  => 'Villa Lembang Sejuk',
                'deskripsi'   => 'Villa nyaman dengan udara sejuk khas dataran tinggi Lembang dan kolam renang air hangat.',
                'kota'        => 'Bandung',
                'harga'       => 1800000,
                'kapasitas'   => 12,
                'status'      => 'aktif',
                'ulasan'      => 4.5,
                'whatsapp'  => '085678901234',
                'instagram' => '@villalembang_official',
                'facebook'  => 'villalembang',
                'tiktok'    => '@villalembang_sejuk',
                'alamat'      => 'Jl. Lembang Indah No. 45, Lembang, Bandung Barat',
            ],
            [
                'nama_villa'  => 'Villa Danau Toba',
                'deskripsi'   => 'Pemandangan Danau Toba yang spektakuler langsung dari teras villa yang luas.',
                'kota'        => 'Medan',
                'harga'       => 1100000,
                'kapasitas'   => 6,
                'status'      => 'aktif',
                'ulasan'      => 4.7,
                'whatsapp'  => '086789012345',
                'instagram' => '@villadanautoba',
                'facebook'  => null,
                'tiktok'    => null,
                'alamat'      => 'Jl. Samosir Raya No. 3, Toba Samosir, Sumatera Utara',
            ],
        ];

        foreach ($villas as $villaData) {
            $id = DB::table('villa')->insertGetId(array_merge($villaData, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));

            // Insert fasilitas untuk tiap villa
            $fasilitasList = [
                'Kolam Renang',
                'WiFi Gratis',
                'Dapur Lengkap',
                'Parkir Luas',
                'AC Setiap Kamar',
                'BBQ Area',
            ];

            foreach ($fasilitasList as $fasilitas) {
                DB::table('fasilitas_villa')->insert([
                    'id_villa'   => $id,
                    'fasilitas'  => $fasilitas,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        $this->command->info('VillaSeeder: 6 villa dummy berhasil dibuat!');
    }
}
