<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KehadiranSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('kehadiran')->insert([
            [
                'siswa_id' => 1,
                'tanggal' => '2024-04-25',
                'waktu_hadir' => '07:30:00',
                'jenis_interaksi' => 'tos',
                'waktu_pulang' => '15:00:00',
                'created_at' => now()->subDay(),
                'updated_at' => now()->subDay(),
            ],
            [
                'siswa_id' => 2,
                'tanggal' => '2024-04-25',
                'waktu_hadir' => '07:45:00',
                'jenis_interaksi' => 'tinju',
                'waktu_pulang' => '15:15:00',
                'created_at' => now()->subDay(),
                'updated_at' => now()->subDay(),
            ],
            [
                'siswa_id' => 3,
                'tanggal' => '2024-04-25',
                'waktu_hadir' => '07:20:00',
                'jenis_interaksi' => 'tos',
                'waktu_pulang' => '14:50:00',
                'created_at' => now()->subDay(),
                'updated_at' => now()->subDay(),
            ],
            [
                'siswa_id' => 1,
                'tanggal' => '2024-04-26',
                'waktu_hadir' => '07:35:00',
                'jenis_interaksi' => 'tos',
                'waktu_pulang' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'siswa_id' => 2,
                'tanggal' => '2024-04-26',
                'waktu_hadir' => '07:50:00',
                'jenis_interaksi' => 'tinju',
                'waktu_pulang' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'siswa_id' => 3,
                'tanggal' => '2024-04-26',
                'waktu_hadir' => '07:25:00',
                'jenis_interaksi' => 'tos',
                'waktu_pulang' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
