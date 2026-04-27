<?php

namespace App\Http\Controllers\Api\Guru;

use App\Http\Controllers\Controller;
use App\Models\DailyReport;
use App\Models\Emosi;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DailyReportController extends Controller
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
        $siswaIds = Siswa::where('guru_id', $mainGuruId)
            ->where('is_active', true)
            ->pluck('id');

        $query = DailyReport::with(['siswa.kelas', 'emosis'])
            ->whereIn('siswa_id', $siswaIds);

        if ($request->has('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('tanggal', [$request->start_date, $request->end_date]);
        }

        if ($request->has('siswa_id')) {
            $query->where('siswa_id', $request->siswa_id);
        }

        $reports = $query->orderBy('tanggal', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $reports,
        ]);
    }

    public function store(Request $request)
    {
        $user = $request->user();
        $guru = $user->guru;

        if (!$guru) {
            return response()->json([
                'success' => false,
                'message' => 'Data guru tidak ditemukan',
            ], 404);
        }

        $validated = $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'tanggal' => 'required|date',
            'mood' => 'nullable|string|max:255',
            'activity' => 'nullable|string',
            'sarapan_pagi' => 'nullable|string|max:255',
            'sarapan_status' => 'nullable|integer|min:1|max:5',
            'makan_siang' => 'nullable|string|max:255',
            'makan_siang_status' => 'nullable|integer|min:1|max:5',
            'snack_sore' => 'nullable|string|max:255',
            'snack_status' => 'nullable|integer|min:1|max:5',
            'minum_air_putih' => 'nullable|string|max:255',
            'minum_susu' => 'nullable|string|max:255',
            'tidur_siang' => 'nullable|boolean',
            'tidur_siang_durasi' => 'nullable|string|max:255',
            'bak' => 'nullable|boolean',
            'bak_frekuensi' => 'nullable|integer|min:0',
            'bab' => 'nullable|boolean',
            'bab_frekuensi' => 'nullable|integer|min:0',
            'kebutuhan_besok' => 'nullable|string',
            'catatan_khusus' => 'nullable|string',
            'catatan_insiden' => 'nullable|string',
            'foto_kegiatan' => 'nullable|array',
            'foto_kegiatan.*' => 'image|max:2048',
            'emosi_ids' => 'nullable|array',
            'emosi_ids.*' => 'exists:emosis,id',
        ]);

        $mainGuruId = $guru->getMainGuruId();
        $siswa = Siswa::where('id', $validated['siswa_id'])
            ->where('guru_id', $mainGuruId)
            ->first();

        if (!$siswa) {
            return response()->json([
                'success' => false,
                'message' => 'Siswa tidak ditemukan atau bukan tanggung jawab Anda',
            ], 403);
        }

        $fotoKegiatan = [];
        if ($request->hasFile('foto_kegiatan')) {
            foreach ($request->file('foto_kegiatan') as $file) {
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('assets/images/foto_kegiatan'), $filename);
                $fotoKegiatan[] = $filename;
            }
        }

        $validated['user_id'] = $user->id;
        $validated['foto_kegiatan'] = !empty($fotoKegiatan) ? json_encode($fotoKegiatan) : null;
        $validated['is_final'] = false;

        $report = DailyReport::create($validated);

        if (isset($validated['emosi_ids'])) {
            $report->emosis()->sync($validated['emosi_ids']);
        }

        $report->load(['siswa.kelas', 'emosis']);

        return response()->json([
            'success' => true,
            'data' => $report,
            'message' => 'Daily report berhasil dibuat',
        ], 201);
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
        $siswaIds = Siswa::where('guru_id', $mainGuruId)
            ->where('is_active', true)
            ->pluck('id');

        $report = DailyReport::with(['siswa.kelas', 'emosis'])
            ->whereIn('siswa_id', $siswaIds)
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $report,
        ]);
    }

    public function update(Request $request, $id)
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
        $siswaIds = Siswa::where('guru_id', $mainGuruId)
            ->where('is_active', true)
            ->pluck('id');

        $report = DailyReport::whereIn('siswa_id', $siswaIds)->findOrFail($id);

        if ($report->is_final) {
            return response()->json([
                'success' => false,
                'message' => 'Daily report yang sudah final tidak dapat diubah',
            ], 403);
        }

        $validated = $request->validate([
            'mood' => 'nullable|string|max:255',
            'activity' => 'nullable|string',
            'sarapan_pagi' => 'nullable|string|max:255',
            'sarapan_status' => 'nullable|integer|min:1|max:5',
            'makan_siang' => 'nullable|string|max:255',
            'makan_siang_status' => 'nullable|integer|min:1|max:5',
            'snack_sore' => 'nullable|string|max:255',
            'snack_status' => 'nullable|integer|min:1|max:5',
            'minum_air_putih' => 'nullable|string|max:255',
            'minum_susu' => 'nullable|string|max:255',
            'tidur_siang' => 'nullable|boolean',
            'tidur_siang_durasi' => 'nullable|string|max:255',
            'bak' => 'nullable|boolean',
            'bak_frekuensi' => 'nullable|integer|min:0',
            'bab' => 'nullable|boolean',
            'bab_frekuensi' => 'nullable|integer|min:0',
            'kebutuhan_besok' => 'nullable|string',
            'catatan_khusus' => 'nullable|string',
            'catatan_insiden' => 'nullable|string',
            'foto_kegiatan' => 'nullable|array',
            'foto_kegiatan.*' => 'image|max:2048',
            'emosi_ids' => 'nullable|array',
            'emosi_ids.*' => 'exists:emosis,id',
        ]);

        $fotoKegiatan = $report->foto_kegiatan ? json_decode($report->foto_kegiatan, true) : [];
        
        if ($request->hasFile('foto_kegiatan')) {
            foreach ($request->file('foto_kegiatan') as $file) {
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('assets/images/foto_kegiatan'), $filename);
                $fotoKegiatan[] = $filename;
            }
        }

        $validated['foto_kegiatan'] = !empty($fotoKegiatan) ? json_encode($fotoKegiatan) : null;

        $report->update($validated);

        if (isset($validated['emosi_ids'])) {
            $report->emosis()->sync($validated['emosi_ids']);
        }

        $report->load(['siswa.kelas', 'emosis']);

        return response()->json([
            'success' => true,
            'data' => $report,
            'message' => 'Daily report berhasil diupdate',
        ]);
    }

    public function finalize(Request $request, $id)
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
        $siswaIds = Siswa::where('guru_id', $mainGuruId)
            ->where('is_active', true)
            ->pluck('id');

        $report = DailyReport::whereIn('siswa_id', $siswaIds)->findOrFail($id);

        if ($report->is_final) {
            return response()->json([
                'success' => false,
                'message' => 'Daily report sudah final',
            ], 400);
        }

        $report->update(['is_final' => true]);

        return response()->json([
            'success' => true,
            'data' => $report,
            'message' => 'Daily report berhasil difinalisasi',
        ]);
    }

    public function getSiswaList(Request $request)
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
        $siswaList = Siswa::with('kelas')
            ->where('guru_id', $mainGuruId)
            ->where('is_active', true)
            ->orderBy('nama_lengkap')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $siswaList,
        ]);
    }

    public function getEmosiList()
    {
        $emosis = Emosi::orderBy('nama_emosi')->get();

        return response()->json([
            'success' => true,
            'data' => $emosis,
        ]);
    }
}
