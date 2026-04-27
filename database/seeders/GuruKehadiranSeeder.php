<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GuruKehadiranSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('guru_kehadiran')->insert([
            [
                'guru_id' => 1,
                'tanggal' => '2024-04-25',
                'check_in' => '07:00:00',
                'check_out' => '15:30:00',
                'catatan' => null,
                'created_at' => now()->subDay(),
                'updated_at' => now()->subDay(),
            ],
            [
                'guru_id' => 2,
                'tanggal' => '2024-04-25',
                'check_in' => '07:05:00',
                'check_out' => '15:35:00',
                'catatan' => null,
                'created_at' => now()->subDay(),
                'updated_at' => now()->subDay(),
            ],
            [
                'guru_id' => 3,
                'tanggal' => '2024-04-25',
                'check_in' => '06:55:00',
                'check_out' => '15:25:00',
                'catatan' => null,
                'created_at' => now()->subDay(),
                'updated_at' => now()->subDay(),
            ],
            [
                'guru_id' => 1,
                'tanggal' => '2024-04-26',
                'check_in' => '07:02:00',
                'check_out' => null,
                'catatan' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'guru_id' => 2,
                'tanggal' => '2024-04-26',
                'check_in' => '07:08:00',
                'check_out' => null,
                'catatan' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'guru_id' => 3,
                'tanggal' => '2024-04-26',
                'check_in' => '06:58:00',
                'check_out' => null,
                'catatan' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
