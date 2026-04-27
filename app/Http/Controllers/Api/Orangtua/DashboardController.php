<?php

namespace App\Http\Controllers\Api\Orangtua;

use App\Http\Controllers\Controller;
use App\Models\DailyReport;
use App\Models\Event;
use App\Models\KegiatanHarian;
use App\Models\News;
use App\Models\PembayaranSpp;
use App\Models\RencanaPembelajaran;
use App\Models\Siswa;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $siswa = Siswa::where('user_id', $user->id)->with(['kelas', 'guru'])->first();

        $kegiatanHariIni = [];
        $notifikasi = [];

        if ($siswa) {
            if ($siswa->kelas_id) {
                $hariIni = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'][now()->timezone('Asia/Jakarta')->dayOfWeek];
                
                $rencanaPembelajaran = RencanaPembelajaran::where('kelas_id', $siswa->kelas_id)
                    ->where('is_active', true)
                    ->orderBy('tanggal_mulai', 'desc')
                    ->first();

                if ($rencanaPembelajaran) {
                    $kegiatanHariIni = KegiatanHarian::where('rencana_pembelajaran_id', $rencanaPembelajaran->id)
                        ->where('hari', $hariIni)
                        ->orderBy('tanggal', 'desc')
                        ->get()
                        ->map(function ($kegiatan) {
                            return [
                                'id' => $kegiatan->id,
                                'nama_aktivitas' => $kegiatan->nama_aktivitas,
                                'deskripsi' => $kegiatan->deskripsi,
                                'target_perkembangan' => $kegiatan->target_perkembangan,
                                'foto_kegiatan' => $kegiatan->foto_kegiatan,
                                'tanggal' => $kegiatan->tanggal->timezone('Asia/Jakarta')->format('Y-m-d'),
                                'hari' => $kegiatan->hari,
                            ];
                        })
                        ->toArray();
                }
            }

            $dailyReports = DailyReport::where('siswa_id', $siswa->id)
                ->orderBy('updated_at', 'desc')
                ->limit(3)
                ->get()
                ->map(function ($report) {
                    return [
                        'id' => $report->id,
                        'type' => 'daily_report',
                        'title' => 'Daily Report Baru',
                        'message' => 'Daily report untuk ' . $report->tanggal->timezone('Asia/Jakarta')->format('d M Y') . ' telah diperbarui',
                        'updated_at' => $report->updated_at->toISOString(),
                        'url' => '/orangtua/daily-report/' . $report->id,
                    ];
                });

            $pembayaranSpp = PembayaranSpp::where('siswa_id', $siswa->id)
                ->orderBy('updated_at', 'desc')
                ->limit(3)
                ->get()
                ->map(function ($spp) {
                    return [
                        'id' => $spp->id,
                        'type' => 'pembayaran_spp',
                        'title' => 'Update Pembayaran SPP',
                        'message' => 'Status pembayaran SPP ' . $spp->bulan . ' ' . $spp->tahun . ': ' . $spp->status_bayar,
                        'updated_at' => $spp->updated_at->toISOString(),
                        'url' => '/orangtua/pembayaran',
                    ];
                });

            $notifikasi = $dailyReports->concat($pembayaranSpp)
                ->sortByDesc('updated_at')
                ->take(5)
                ->values()
                ->toArray();
        }

        $latestNews = News::where('is_published', true)
            ->orderBy('published_at', 'desc')
            ->limit(3)
            ->get()
            ->map(function ($news) {
                return [
                    'id' => $news->id,
                    'title' => $news->title,
                    'excerpt' => $news->excerpt,
                    'image_url' => $news->image_url,
                    'slug' => $news->slug,
                    'published_at' => $news->published_at->timezone('Asia/Jakarta')->format('Y-m-d'),
                ];
            });

        $activeEvents = Event::active()
            ->orderBy('priority', 'asc')
            ->get()
            ->map(function ($event) {
                return [
                    'id' => $event->id,
                    'title' => $event->title,
                    'description' => $event->description,
                    'image_url' => $event->image_url,
                    'start_date' => $event->start_date->timezone('Asia/Jakarta')->format('Y-m-d'),
                    'end_date' => $event->end_date->timezone('Asia/Jakarta')->format('Y-m-d'),
                ];
            });

        $dailyReportCount = $siswa ? DailyReport::where('siswa_id', $siswa->id)
            ->whereDate('created_at', '>=', now()->timezone('Asia/Jakarta')->subDays(7))
            ->count() : 0;

        $pembayaranPending = $siswa ? PembayaranSpp::where('siswa_id', $siswa->id)
            ->where('status_bayar', 'belum_bayar')
            ->count() : 0;

        return response()->json([
            'success' => true,
            'data' => [
                'siswa' => $siswa ? [
                    'id' => $siswa->id,
                    'nama_lengkap' => $siswa->nama_lengkap,
                    'nama_panggilan' => $siswa->nama_panggilan,
                    'foto_siswa' => $siswa->foto_siswa ? url('assets/images/foto_siswa/' . $siswa->foto_siswa) : null,
                    'is_active' => $siswa->is_active,
                    'kelas' => $siswa->kelas ? [
                        'id' => $siswa->kelas->id,
                        'nama_kelas' => $siswa->kelas->nama_kelas,
                    ] : null,
                    'guru' => $siswa->guru ? [
                        'id' => $siswa->guru->id,
                        'nama_lengkap' => $siswa->guru->nama_lengkap,
                    ] : null,
                ] : null,
                'kegiatan_hari_ini' => $kegiatanHariIni,
                'notifikasi' => [
                    'daily_report_count' => $dailyReportCount,
                    'pembayaran_pending' => $pembayaranPending,
                ],
                'berita_terbaru' => $latestNews,
                'event_aktif' => $activeEvents,
            ],
            'message' => 'Dashboard data retrieved successfully',
        ]);
    }
}
