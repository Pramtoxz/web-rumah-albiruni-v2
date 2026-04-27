<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class NewsSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('news')->insert([
            [
                'title' => 'Pembukaan Tahun Ajaran Baru 2024/2025',
                'excerpt' => 'Albiruni Pre-School membuka tahun ajaran baru dengan penuh semangat dan inovasi pembelajaran terkini.',
                'content' => '<p>Albiruni Pre-School dengan bangga mengumumkan pembukaan tahun ajaran baru 2024/2025. Tahun ini kami menghadirkan berbagai program pembelajaran inovatif yang dirancang khusus untuk mengoptimalkan perkembangan anak usia dini.</p><p>Program-program baru meliputi pembelajaran berbasis STEAM, pengembangan karakter Islami, dan aktivitas outdoor learning yang menyenangkan.</p>',
                'image' => null,
                'slug' => Str::slug('Pembukaan Tahun Ajaran Baru 2024/2025'),
                'is_published' => true,
                'published_at' => now()->subDays(10),
                'created_at' => now()->subDays(10),
                'updated_at' => now()->subDays(10),
            ],
            [
                'title' => 'Workshop Parenting: Mendidik Anak di Era Digital',
                'excerpt' => 'Mengundang seluruh orang tua untuk mengikuti workshop parenting tentang mendidik anak di era digital.',
                'content' => '<p>Albiruni Pre-School mengadakan workshop parenting dengan tema "Mendidik Anak di Era Digital" pada tanggal 15 April 2024. Workshop ini akan membahas strategi mendidik anak yang sehat dan seimbang di tengah perkembangan teknologi.</p><p>Narasumber: Dr. Siti Rahmah, M.Psi - Psikolog Anak dan Keluarga. Tempat: Aula Albiruni Pre-School. Waktu: 09.00 - 12.00 WIB.</p>',
                'image' => null,
                'slug' => Str::slug('Workshop Parenting: Mendidik Anak di Era Digital'),
                'is_published' => true,
                'published_at' => now()->subDays(5),
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5),
            ],
            [
                'title' => 'Prestasi Siswa Albiruni di Lomba Mewarnai Tingkat Kota',
                'excerpt' => 'Siswa Albiruni Pre-School meraih juara 1 dan 2 dalam lomba mewarnai tingkat kota Padang.',
                'content' => '<p>Kami dengan bangga mengumumkan prestasi gemilang siswa-siswi Albiruni Pre-School dalam Lomba Mewarnai Tingkat Kota Padang yang diselenggarakan pada 20 April 2024.</p><p>Juara 1: Aisyah Putri Dahlan (Kelas Nursery)<br>Juara 2: Budi Santoso Junior (Kelas Kindergarten A)</p><p>Selamat kepada para juara! Terus berkarya dan berprestasi.</p>',
                'image' => null,
                'slug' => Str::slug('Prestasi Siswa Albiruni di Lomba Mewarnai Tingkat Kota'),
                'is_published' => true,
                'published_at' => now()->subDays(2),
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(2),
            ],
        ]);
    }
}
