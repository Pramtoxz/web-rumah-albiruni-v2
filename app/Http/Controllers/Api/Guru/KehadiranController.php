<?php

namespace App\Http\Controllers\Api\Guru;

use App\Http\Controllers\Controller;
use App\Models\GuruKehadiran;
use Illuminate\Http\Request;

class KehadiranController extends Controller
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

        $todayAttendance = GuruKehadiran::where('guru_id', $guru->id)
            ->whereDate('tanggal', $today)
            ->first();

        $recentAttendance = GuruKehadiran::where('guru_id', $guru->id)
            ->orderBy('tanggal', 'desc')
            ->limit(30)
            ->get()
            ->map(function ($attendance) {
                return [
                    'id' => $attendance->id,
                    'tanggal' => $attendance->tanggal->timezone('Asia/Jakarta')->format('Y-m-d'),
                    'tanggal_formatted' => $attendance->tanggal->timezone('Asia/Jakarta')->locale('id')->isoFormat('dddd, D MMMM YYYY'),
                    'check_in' => $attendance->check_in,
                    'check_out' => $attendance->check_out,
                    'catatan' => $attendance->catatan,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => [
                'todayAttendance' => $todayAttendance ? [
                    'id' => $todayAttendance->id,
                    'tanggal' => $todayAttendance->tanggal->timezone('Asia/Jakarta')->format('Y-m-d'),
                    'check_in' => $todayAttendance->check_in,
                    'check_out' => $todayAttendance->check_out,
                    'catatan' => $todayAttendance->catatan,
                ] : null,
                'recentAttendance' => $recentAttendance,
                'currentTime' => now()->timezone('Asia/Jakarta')->format('H:i:s'),
            ],
        ]);
    }

    public function checkIn(Request $request)
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
        $currentTime = now()->timezone('Asia/Jakarta')->format('H:i:s');

        $existing = GuruKehadiran::where('guru_id', $guru->id)
            ->whereDate('tanggal', $today)
            ->first();

        if ($existing && $existing->check_in) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah melakukan check-in hari ini',
            ], 400);
        }

        $validated = $request->validate([
            'catatan' => 'nullable|string|max:500',
        ]);

        $attendance = GuruKehadiran::updateOrCreate(
            [
                'guru_id' => $guru->id,
                'tanggal' => $today,
            ],
            [
                'check_in' => $currentTime,
                'catatan' => $validated['catatan'] ?? null,
            ]
        );

        return response()->json([
            'success' => true,
            'data' => $attendance,
            'message' => 'Check-in berhasil!',
        ]);
    }

    public function checkOut(Request $request)
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
        $currentTime = now()->timezone('Asia/Jakarta')->format('H:i:s');

        $attendance = GuruKehadiran::where('guru_id', $guru->id)
            ->whereDate('tanggal', $today)
            ->first();

        if (!$attendance || !$attendance->check_in) {
            return response()->json([
                'success' => false,
                'message' => 'Anda belum melakukan check-in hari ini',
            ], 400);
        }

        if ($attendance->check_out) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah melakukan check-out hari ini',
            ], 400);
        }

        $validated = $request->validate([
            'catatan' => 'nullable|string|max:500',
        ]);

        $attendance->update([
            'check_out' => $currentTime,
            'catatan' => $validated['catatan'] ?? $attendance->catatan,
        ]);

        return response()->json([
            'success' => true,
            'data' => $attendance,
            'message' => 'Check-out berhasil!',
        ]);
    }
}
