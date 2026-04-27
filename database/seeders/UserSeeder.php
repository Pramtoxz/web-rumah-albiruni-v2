<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Admin Albiruni',
                'email' => 'admin@albiruni.sch.id',
                'password' => Hash::make('password'),
                'nohp' => '6281234567890',
                'role' => 'admin',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Siti Nurhaliza',
                'email' => 'siti.guru@albiruni.sch.id',
                'password' => Hash::make('password'),
                'nohp' => '6281234567891',
                'role' => 'guru',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Rina Wati',
                'email' => 'rina.guru@albiruni.sch.id',
                'password' => Hash::make('password'),
                'nohp' => '6281234567892',
                'role' => 'guru',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Dewi Sartika',
                'email' => 'dewi.guru@albiruni.sch.id',
                'password' => Hash::make('password'),
                'nohp' => '6281234567893',
                'role' => 'guru',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Ahmad Dahlan',
                'email' => 'ahmad.orangtua@gmail.com',
                'password' => Hash::make('password'),
                'nohp' => '6281234567894',
                'role' => 'orangtua',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Budi Santoso',
                'email' => 'budi.orangtua@gmail.com',
                'password' => Hash::make('password'),
                'nohp' => '6281234567895',
                'role' => 'orangtua',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Citra Dewi',
                'email' => 'citra.orangtua@gmail.com',
                'password' => Hash::make('password'),
                'nohp' => '6281234567896',
                'role' => 'orangtua',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
