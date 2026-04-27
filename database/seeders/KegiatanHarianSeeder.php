<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KegiatanHarianSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('kegiatan_harian')->insert([
            [
                'rencana_pembelajaran_id' => 1,
                'hari' => 'senin',
                'tanggal' => '2024-04-01',
                'nama_aktivitas' => 'Mengenal Nama Sendiri',
                'deskripsi' => 'Anak-anak belajar menyebutkan dan menulis nama mereka sendiri',
                'target_perkembangan' => 'Anak mampu menyebutkan nama lengkap dan nama panggilan',
                'alat_bahan' => 'Kertas, pensil warna, spidol, kartu nama',
                'instruksi' => 'Guru memperkenalkan diri, lalu meminta setiap anak menyebutkan namanya. Anak diminta menghias kartu nama mereka.',
                'foto_kegiatan' => null,
                'video_url' => null,
                'file_materi' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'rencana_pembelajaran_id' => 1,
                'hari' => 'selasa',
                'tanggal' => '2024-04-02',
                'nama_aktivitas' => 'Mengenal Anggota Tubuh',
                'deskripsi' => 'Anak-anak belajar menyebutkan nama-nama anggota tubuh',
                'target_perkembangan' => 'Anak mampu menyebutkan minimal 10 anggota tubuh',
                'alat_bahan' => 'Poster tubuh manusia, lagu anggota tubuh, cermin',
                'instruksi' => 'Guru menyanyikan lagu anggota tubuh sambil menunjuk bagian tubuh. Anak menirukan dan menyebutkan nama anggota tubuh.',
                'foto_kegiatan' => null,
                'video_url' => null,
                'file_materi' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'rencana_pembelajaran_id' => 3,
                'hari' => 'senin',
                'tanggal' => '2024-04-01',
                'nama_aktivitas' => 'Mengenal Kucing',
                'deskripsi' => 'Anak-anak belajar tentang karakteristik kucing',
                'target_perkembangan' => 'Anak mampu menyebutkan ciri-ciri kucing',
                'alat_bahan' => 'Gambar kucing, boneka kucing, video kucing',
                'instruksi' => 'Guru menunjukkan gambar dan video kucing. Anak diminta menyebutkan ciri-ciri kucing seperti berkaki empat, berbulu, dll.',
                'foto_kegiatan' => null,
                'video_url' => null,
                'file_materi' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
