<?php

namespace App\Http\Controllers\Api\Guru;

use App\Http\Controllers\Controller;
use App\Models\RencanaPembelajaran;
use App\Models\Siswa;
use Illuminate\Http\Request;

class RencanaPembelajaranController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $guru = $user->guru;

        if (!$guru) {
            return response()->json([
                'success' => false,
                'message' => 'Data guru tidak ditemukan',
            ], 404);
        }

        $mainGuruId = $guru->getMainGuruId();
        $kelasIds = Siswa::where('guru_id', $mainGuruId)
            ->where('is_active', true)
            ->pluck('kelas_id')
            ->unique();

        $rencanaList = RencanaPembelajaran::with('kelas')
            ->whereIn('kelas_id', $kelasIds)
            ->orderBy('tanggal_mulai', 'desc')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $rencanaList,
        ]);
    }

    public function show(Request $request, $id)
    {
        $user = $request->user();
        $guru = $user->guru;

        if (!$guru) {
            return response()->json([
                'success' => false,
                'message' => 'Data guru tidak ditemukan',
            ], 404);
        }

        $mainGuruId = $guru->getMainGuruId();
        $kelasIds = Siswa::where('guru_id', $mainGuruId)
            ->where('is_active', true)
            ->pluck('kelas_id')
            ->unique();

        $rencana = RencanaPembelajaran::with(['kelas', 'kegiatanHarian'])
            ->whereIn('kelas_id', $kelasIds)
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $rencana,
        ]);
    }
}
