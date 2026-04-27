<?php

namespace App\Http\Controllers\Api\Guru;

use App\Http\Controllers\Controller;
use App\Models\DailyReport;
use App\Models\Kehadiran;
use App\Models\KegiatanHarian;
use App\Models\Siswa;
use Illuminate\Http\Request;

class DashboardController extends Controller
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

        $today = now()->timezone('Asia/Jakarta')->toDateString();
        $mainGuruId = $guru->getMainGuruId();

        $totalSiswa = Siswa::where('guru_id', $mainGuruId)
            ->where('is_active', true)
            ->count();

        $siswaHadir = Kehadiran::whereDate('tanggal', $today)
            ->whereHas('siswa', function ($query) use ($mainGuruId) {
                $query->where('guru_id', $mainGuruId)
                    ->where('is_active', true);
            })
            ->count();

        $siswaIds = Siswa::where('guru_id', $mainGuruId)
            ->where('is_active', true)
            ->pluck('id');

        $totalDailyReports = $siswaIds->count();

        $completedDailyReports = DailyReport::whereDate('tanggal', $today)
            ->whereIn('siswa_id', $siswaIds)
            ->count();

        $kelasIds = Siswa::where('guru_id', $mainGuruId)
            ->where('is_active', true)
            ->pluck('kelas_id')
            ->unique();

        $totalMateri = KegiatanHarian::whereDate('tanggal', $today)
            ->whereHas('rencanaPembelajaran', function ($query) use ($kelasIds) {
                $query->whereIn('kelas_id', $kelasIds);
            })
            ->count();

        $completedMateri = $totalMateri;

        $recentReports = DailyReport::with(['siswa.kelas'])
            ->whereDate('tanggal', $today)
            ->whereHas('siswa', function ($query) use ($mainGuruId) {
                $query->where('guru_id', $mainGuruId);
            })
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($report) {
                return [
                    'id' => $report->id,
                    'siswa_id' => $report->siswa_id,
                    'tanggal' => $report->tanggal->timezone('Asia/Jakarta')->format('Y-m-d'),
                    'mood' => $report->mood,
                    'activity' => $report->activity ?? 'Daily Report',
                    'is_final' => $report->is_final,
                    'siswa' => [
                        'id' => $report->siswa->id,
                        'nama_lengkap' => $report->siswa->nama_lengkap,
                        'nama_panggilan' => $report->siswa->nama_panggilan,
                    ],
                    'created_at' => $report->created_at->timezone('Asia/Jakarta')->toISOString(),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => [
                'stats' => [
                    'total_siswa' => $totalSiswa,
                    'siswa_hadir' => $siswaHadir,
                    'daily_report_completed' => $completedDailyReports,
                    'daily_report_total' => $totalDailyReports,
                    'materi_completed' => $completedMateri,
                    'materi_total' => $totalMateri,
                ],
                'recent_reports' => $recentReports,
            ],
            'message' => 'Dashboard data retrieved successfully',
        ]);
    }
}
