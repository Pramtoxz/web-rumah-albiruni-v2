<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KelasSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('kelas')->insert([
            [
                'nama_kelas' => 'Toddler',
                'kategori' => 'bayi',
                'deskripsi' => 'Kelas untuk anak usia 1-2 tahun',
                'spp' => 1500000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kelas' => 'Nursery',
                'kategori' => 'anak',
                'deskripsi' => 'Kelas untuk anak usia 2-3 tahun',
                'spp' => 1800000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kelas' => 'Kindergarten A',
                'kategori' => 'anak',
                'deskripsi' => 'Kelas untuk anak usia 4-5 tahun',
                'spp' => 2000000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kelas' => 'Kindergarten B',
                'kategori' => 'anak',
                'deskripsi' => 'Kelas untuk anak usia 5-6 tahun',
                'spp' => 2200000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
