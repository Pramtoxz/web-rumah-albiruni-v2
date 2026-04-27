<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('events')->insert([
            [
                'title' => 'Perayaan Hari Kartini',
                'description' => 'Perayaan Hari Kartini dengan mengenakan pakaian adat dan lomba-lomba menarik untuk anak-anak.',
                'image_url' => null,
                'start_date' => '2024-04-21',
                'end_date' => '2024-04-21',
                'is_active' => true,
                'priority' => 1,
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Field Trip ke Kebun Binatang',
                'description' => 'Kunjungan edukatif ke Kebun Binatang Bukittinggi untuk mengenal berbagai jenis hewan.',
                'image_url' => null,
                'start_date' => '2024-05-10',
                'end_date' => '2024-05-10',
                'is_active' => true,
                'priority' => 2,
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Pekan Olahraga dan Seni',
                'description' => 'Pekan olahraga dan seni dengan berbagai lomba seperti lari, menggambar, menyanyi, dan menari.',
                'image_url' => null,
                'start_date' => '2024-05-20',
                'end_date' => '2024-05-24',
                'is_active' => true,
                'priority' => 3,
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Libur Lebaran Idul Fitri',
                'description' => 'Libur sekolah dalam rangka perayaan Hari Raya Idul Fitri 1445 H.',
                'image_url' => null,
                'start_date' => '2024-04-08',
                'end_date' => '2024-04-19',
                'is_active' => false,
                'priority' => 0,
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
