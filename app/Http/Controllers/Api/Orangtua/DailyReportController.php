<?php

namespace App\Http\Controllers\Api\Orangtua;

use App\Http\Controllers\Controller;
use App\Models\DailyReport;
use App\Models\Siswa;
use Illuminate\Http\Request;

class DailyReportController extends Controller
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

        $query = DailyReport::with(['siswa.kelas', 'emosis'])
            ->where('siswa_id', $siswa->id);

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

        $reports = $query->orderBy('tanggal', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $reports,
        ]);
    }

    public function show(Request $request, $id)
    {
        $user = $request->user();
        $siswa = Siswa::where('user_id', $user->id)->first();

        if (!$siswa) {
            return response()->json([
                'success' => false,
                'message' => 'Data siswa tidak ditemukan',
            ], 404);
        }

        $report = DailyReport::with(['siswa.kelas', 'emosis'])
            ->where('siswa_id', $siswa->id)
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $report,
        ]);
    }
}
