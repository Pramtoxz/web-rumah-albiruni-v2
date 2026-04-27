<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GuruSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('gurus')->insert([
            [
                'user_id' => 2,
                'guru_utama_id' => null,
                'kelas_id' => 2,
                'nip' => '198501012010012001',
                'nama_lengkap' => 'Siti Nurhaliza, S.Pd',
                'tempat_lahir' => 'Padang',
                'tanggal_lahir' => '1985-01-01',
                'jenis_kelamin' => 'P',
                'alamat' => 'Jl. Veteran No. 12, Padang',
                'pendidikan_terakhir' => 'S1 Pendidikan Anak Usia Dini',
                'foto_guru' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 3,
                'guru_utama_id' => 1,
                'kelas_id' => 2,
                'nip' => '199002152015032002',
                'nama_lengkap' => 'Rina Wati, S.Pd',
                'tempat_lahir' => 'Bukittinggi',
                'tanggal_lahir' => '1990-02-15',
                'jenis_kelamin' => 'P',
                'alamat' => 'Jl. Sudirman No. 45, Padang',
                'pendidikan_terakhir' => 'S1 Pendidikan Guru PAUD',
                'foto_guru' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 4,
                'guru_utama_id' => null,
                'kelas_id' => 3,
                'nip' => '198803202012032003',
                'nama_lengkap' => 'Dewi Sartika, S.Pd',
                'tempat_lahir' => 'Padang',
                'tanggal_lahir' => '1988-03-20',
                'jenis_kelamin' => 'P',
                'alamat' => 'Jl. Khatib Sulaiman No. 78, Padang',
                'pendidikan_terakhir' => 'S1 Psikologi Pendidikan',
                'foto_guru' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
