<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            CabangSeeder::class,
            EmosiSeeder::class,
            KelasSeeder::class,
            UserSeeder::class,
            GuruSeeder::class,
            SiswaSeeder::class,
            RencanaPembelajaranSeeder::class,
            KegiatanHarianSeeder::class,
            NewsSeeder::class,
            EventSeeder::class,
            DailyReportSeeder::class,
            KehadiranSeeder::class,
            GuruKehadiranSeeder::class,
            PembayaranSppSeeder::class,
        ]);
    }
}
