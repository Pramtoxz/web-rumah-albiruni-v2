<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RencanaPembelajaranSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('rencana_pembelajaran')->insert([
            [
                'nama_rencana' => 'Rencana Pembelajaran Minggu 1',
                'tema' => 'Diri Sendiri',
                'sub_tema' => 'Identitas Diri',
                'tanggal_mulai' => '2024-04-01',
                'tanggal_selesai' => '2024-04-05',
                'kelas_id' => 2,
                'is_active' => true,
                'created_by' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_rencana' => 'Rencana Pembelajaran Minggu 2',
                'tema' => 'Lingkunganku',
                'sub_tema' => 'Keluarga',
                'tanggal_mulai' => '2024-04-08',
                'tanggal_selesai' => '2024-04-12',
                'kelas_id' => 2,
                'is_active' => false,
                'created_by' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_rencana' => 'Rencana Pembelajaran Minggu 1',
                'tema' => 'Binatang',
                'sub_tema' => 'Binatang Peliharaan',
                'tanggal_mulai' => '2024-04-01',
                'tanggal_selesai' => '2024-04-05',
                'kelas_id' => 3,
                'is_active' => true,
                'created_by' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
