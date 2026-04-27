<?php

namespace App\Http\Controllers\Api\Orangtua;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $siswa = $user->siswa;

        if (!$siswa) {
            return response()->json([
                'success' => false,
                'message' => 'Data siswa tidak ditemukan',
            ], 404);
        }

        $orangtuaData = null;
        $hubungan = null;

        if ($siswa->ayah_no_hp === $user->nohp) {
            $orangtuaData = [
                'nama_lengkap' => $siswa->ayah_nama_lengkap,
                'pekerjaan' => $siswa->ayah_pekerjaan,
                'alamat' => $siswa->ayah_alamat_rumah,
            ];
            $hubungan = 'Ayah';
        } elseif ($siswa->ibu_no_hp === $user->nohp) {
            $orangtuaData = [
                'nama_lengkap' => $siswa->ibu_nama_lengkap,
                'pekerjaan' => $siswa->ibu_pekerjaan,
                'alamat' => $siswa->ibu_alamat_rumah,
            ];
            $hubungan = 'Ibu';
        } else {
            $orangtuaData = [
                'nama_lengkap' => $user->name,
                'pekerjaan' => null,
                'alamat' => null,
            ];
            $hubungan = 'Wali';
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $user->id,
                'name' => $orangtuaData['nama_lengkap'] ?? $user->name,
                'email' => $user->email,
                'nohp' => $user->nohp,
                'role' => $user->role,
                'foto_profil' => null,
                'alamat' => $orangtuaData['alamat'],
                'pekerjaan' => $orangtuaData['pekerjaan'],
                'hubungan_dengan_siswa' => $hubungan,
                'created_at' => $user->created_at->toISOString(),
            ],
            'message' => 'Profile retrieved successfully',
        ]);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
            'nohp' => 'sometimes|string|max:20|unique:users,nohp,' . $user->id,
        ]);

        $user->update($validated);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'nohp' => $user->nohp,
                'role' => $user->role,
            ],
            'message' => 'Profile berhasil diupdate',
        ]);
    }

    public function siswa(Request $request)
    {
        $user = $request->user();
        $siswa = $user->siswa()->with(['kelas', 'guru'])->first();

        if (!$siswa) {
            return response()->json([
                'success' => false,
                'message' => 'Data siswa tidak ditemukan',
            ], 404);
        }

        $orangtua = [];
        
        if ($siswa->ayah_nama_lengkap && $siswa->ayah_no_hp) {
            $orangtua[] = [
                'id' => null,
                'name' => $siswa->ayah_nama_lengkap,
                'hubungan' => 'Ayah',
                'nohp' => $siswa->ayah_no_hp,
            ];
        }

        if ($siswa->ibu_nama_lengkap && $siswa->ibu_no_hp) {
            $orangtua[] = [
                'id' => null,
                'name' => $siswa->ibu_nama_lengkap,
                'hubungan' => 'Ibu',
                'nohp' => $siswa->ibu_no_hp,
            ];
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $siswa->id,
                'nama_lengkap' => $siswa->nama_lengkap,
                'nama_panggilan' => $siswa->nama_panggilan,
                'tanggal_lahir' => $siswa->tanggal_lahir?->timezone('Asia/Jakarta')->format('Y-m-d'),
                'jenis_kelamin' => $siswa->jenis_kelamin,
                'foto_siswa' => $siswa->foto_siswa ? url('assets/images/foto_siswa/' . $siswa->foto_siswa) : null,
                'alamat' => $siswa->ayah_alamat_rumah ?? $siswa->ibu_alamat_rumah,
                'is_active' => $siswa->is_active,
                'kelas' => $siswa->kelas ? [
                    'id' => $siswa->kelas->id,
                    'nama_kelas' => $siswa->kelas->nama_kelas,
                ] : null,
                'guru' => $siswa->guru ? [
                    'id' => $siswa->guru->id,
                    'nama_lengkap' => $siswa->guru->nama_lengkap,
                ] : null,
                'orangtua' => $orangtua,
                'riwayat_kesehatan' => [
                    'alergi' => $siswa->riwayat_alergi_makanan,
                    'penyakit_bawaan' => $siswa->riwayat_penyakit,
                    'golongan_darah' => $siswa->golongan_darah,
                ],
                'created_at' => $siswa->created_at->toISOString(),
            ],
            'message' => 'Student profile retrieved successfully',
        ]);
    }
}
