<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DeviceTokenController;
use App\Http\Controllers\Api\Guru\DashboardController as GuruDashboardController;
use App\Http\Controllers\Api\Guru\DailyReportController as GuruDailyReportController;
use App\Http\Controllers\Api\Guru\KehadiranController as GuruKehadiranController;
use App\Http\Controllers\Api\Guru\RencanaPembelajaranController as GuruRencanaPembelajaranController;
use App\Http\Controllers\Api\Guru\ProfileController as GuruProfileController;
use App\Http\Controllers\Api\Orangtua\DashboardController as OrangtuaDashboardController;
use App\Http\Controllers\Api\Orangtua\DailyReportController as OrangtuaDailyReportController;
use App\Http\Controllers\Api\Orangtua\KegiatanHarianController as OrangtuaKegiatanHarianController;
use App\Http\Controllers\Api\Orangtua\KehadiranController as OrangtuaKehadiranController;
use App\Http\Controllers\Api\Orangtua\NewsController as OrangtuaNewsController;
use App\Http\Controllers\Api\Orangtua\PembayaranSppController as OrangtuaPembayaranSppController;
use App\Http\Controllers\Api\Orangtua\ProfileController as OrangtuaProfileController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::post('otp/send', [AuthController::class, 'sendOtp']);
    Route::post('login', [AuthController::class, 'login']);

    Route::middleware(['auth:sanctum', 'block.admin'])->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('user', [AuthController::class, 'user']);

        Route::post('device-tokens', [DeviceTokenController::class, 'store']);
        Route::delete('device-tokens', [DeviceTokenController::class, 'destroy']);

        Route::prefix('guru')->middleware('role:guru')->group(function () {
            Route::get('dashboard', [GuruDashboardController::class, 'index']);

            Route::get('profile', [GuruProfileController::class, 'index']);
            Route::put('profile', [GuruProfileController::class, 'update']);
            Route::post('profile', [GuruProfileController::class, 'update']);

            Route::prefix('daily-report')->group(function () {
                Route::get('/', [GuruDailyReportController::class, 'index']);
                Route::post('/', [GuruDailyReportController::class, 'store']);
                Route::get('siswa-list', [GuruDailyReportController::class, 'getSiswaList']);
                Route::get('emosi-list', [GuruDailyReportController::class, 'getEmosiList']);
                Route::get('{id}', [GuruDailyReportController::class, 'show']);
                Route::put('{id}', [GuruDailyReportController::class, 'update']);
                Route::post('{id}/finalize', [GuruDailyReportController::class, 'finalize']);
            });

            Route::prefix('absensi')->group(function () {
                Route::get('/', [GuruKehadiranController::class, 'index']);
                Route::post('check-in', [GuruKehadiranController::class, 'checkIn']);
                Route::post('check-out', [GuruKehadiranController::class, 'checkOut']);
            });

            Route::prefix('rencana-pembelajaran')->group(function () {
                Route::get('/', [GuruRencanaPembelajaranController::class, 'index']);
                Route::get('{id}', [GuruRencanaPembelajaranController::class, 'show']);
            });
        });

        Route::prefix('orangtua')->middleware('role:orangtua')->group(function () {
            Route::get('dashboard', [OrangtuaDashboardController::class, 'index']);

            Route::get('profile', [OrangtuaProfileController::class, 'index']);
            Route::put('profile', [OrangtuaProfileController::class, 'update']);
            Route::get('siswa/profile', [OrangtuaProfileController::class, 'siswa']);

            Route::prefix('daily-report')->group(function () {
                Route::get('/', [OrangtuaDailyReportController::class, 'index']);
                Route::get('{id}', [OrangtuaDailyReportController::class, 'show']);
            });

            Route::prefix('kegiatan-harian')->group(function () {
                Route::get('/', [OrangtuaKegiatanHarianController::class, 'index']);
            });

            Route::prefix('pembayaran')->group(function () {
                Route::get('/', [OrangtuaPembayaranSppController::class, 'index']);
                Route::post('{id}/upload', [OrangtuaPembayaranSppController::class, 'upload']);
            });

            Route::prefix('absensi')->group(function () {
                Route::get('/', [OrangtuaKehadiranController::class, 'index']);
            });

            Route::prefix('berita')->group(function () {
                Route::get('/', [OrangtuaNewsController::class, 'index']);
                Route::get('{slug}', [OrangtuaNewsController::class, 'show']);
            });
        });
    });
});
