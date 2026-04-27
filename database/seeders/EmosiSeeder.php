<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmosiSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('emosis')->insert([
            [
                'nama_emosi' => 'Senang',
                'deskripsi' => 'Anak terlihat ceria dan bahagia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_emosi' => 'Sedih',
                'deskripsi' => 'Anak terlihat murung atau menangis',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_emosi' => 'Marah',
                'deskripsi' => 'Anak terlihat kesal atau tantrum',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_emosi' => 'Takut',
                'deskripsi' => 'Anak terlihat cemas atau ketakutan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_emosi' => 'Bosan',
                'deskripsi' => 'Anak terlihat tidak tertarik dengan aktivitas',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_emosi' => 'Antusias',
                'deskripsi' => 'Anak terlihat sangat bersemangat',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
