<?php

namespace App\Http\Controllers\Api\Orangtua;

use App\Http\Controllers\Controller;
use App\Models\KegiatanHarian;
use App\Models\RencanaPembelajaran;
use App\Models\Siswa;
use Illuminate\Http\Request;

class KegiatanHarianController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $siswa = Siswa::where('user_id', $user->id)->with('kelas')->first();

        if (!$siswa) {
            return response()->json([
                'success' => false,
                'message' => 'Data siswa tidak ditemukan',
            ], 404);
        }

        $kegiatanList = [];

        if ($siswa->kelas_id) {
            $hariIni = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'][now()->timezone('Asia/Jakarta')->dayOfWeek];

            $rencanaPembelajaran = RencanaPembelajaran::where('kelas_id', $siswa->kelas_id)
                ->active()
                ->whereDate('tanggal_mulai', '<=', now()->timezone('Asia/Jakarta'))
                ->whereDate('tanggal_selesai', '>=', now()->timezone('Asia/Jakarta'))
                ->first();

            if ($rencanaPembelajaran) {
                $kegiatanList = KegiatanHarian::where('rencana_pembelajaran_id', $rencanaPembelajaran->id)
                    ->where('hari', $hariIni)
                    ->orderBy('tanggal', 'desc')
                    ->get()
                    ->map(function ($kegiatan) {
                        return [
                            'id' => $kegiatan->id,
                            'nama_aktivitas' => $kegiatan->nama_aktivitas,
                            'deskripsi' => $kegiatan->deskripsi,
                            'target_perkembangan' => $kegiatan->target_perkembangan,
                            'alat_bahan' => $kegiatan->alat_bahan,
                            'instruksi' => $kegiatan->instruksi,
                            'foto_kegiatan' => $kegiatan->foto_kegiatan,
                            'tanggal' => $kegiatan->tanggal->timezone('Asia/Jakarta')->format('Y-m-d'),
                            'hari' => $kegiatan->hari,
                        ];
                    });
            }
        }

        return response()->json([
            'success' => true,
            'data' => [
                'kegiatanList' => $kegiatanList,
                'siswa' => [
                    'nama_lengkap' => $siswa->nama_lengkap,
                    'nama_panggilan' => $siswa->nama_panggilan,
                    'kelas' => $siswa->kelas ? [
                        'nama_kelas' => $siswa->kelas->nama_kelas,
                    ] : null,
                ],
            ],
        ]);
    }
}
