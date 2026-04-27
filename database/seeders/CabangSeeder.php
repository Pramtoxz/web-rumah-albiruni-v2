<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CabangSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('cabangs')->insert([
            [
                'nama_cabang' => 'Ulak Karang',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_cabang' => 'Marapalam',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
