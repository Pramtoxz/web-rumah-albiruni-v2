<?php

namespace App\Http\Controllers\Api\Orangtua;

use App\Http\Controllers\Controller;
use App\Models\Kehadiran;
use App\Models\Siswa;
use Illuminate\Http\Request;

class KehadiranController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $siswa = Siswa::where('user_id', $user->id)->first();

        if (!$siswa) {
            return response()->json([
                'success' => false,
                'message' => 'Data siswa tidak ditemukan',
            ], 404);
        }

        $query = Kehadiran::where('siswa_id', $siswa->id);

        if ($request->has('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('tanggal', [$request->start_date, $request->end_date]);
        }

        if ($request->has('bulan') && $request->has('tahun')) {
            $query->whereMonth('tanggal', $request->bulan)
                  ->whereYear('tanggal', $request->tahun);
        }

        $kehadiran = $query->orderBy('tanggal', 'desc')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => [
                'kehadiran' => $kehadiran,
                'siswa' => $siswa,
            ],
        ]);
    }
}
