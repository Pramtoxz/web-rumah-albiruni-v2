<?php

namespace App\Http\Controllers\Api;

use App\Enums\OtpType;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function __construct(
        protected OtpService $otpService
    ) {
    }

    public function sendOtp(Request $request)
    {
        $validated = $request->validate([
            'nohp' => 'required|string|max:20',
        ]);

        $phone = $this->otpService->normalizePhone($validated['nohp']);

        $exists = User::where('nohp', $phone)->exists();

        if (!$exists) {
            throw ValidationException::withMessages([
                'nohp' => ['Nomor WhatsApp belum terdaftar.'],
            ]);
        }

        $this->otpService->send($phone, OtpType::Login);

        return response()->json([
            'success' => true,
            'message' => 'Kode OTP telah dikirim ke WhatsApp Anda.',
        ]);
    }

    public function login(Request $request)
    {
        $request->merge([
            'nohp' => $this->otpService->normalizePhone($request->string('nohp')->toString()),
        ]);

        $bypassOtp = config('app.env') === 'local' && config('app.bypass_otp_in_dev') === true;

        $validated = $request->validate([
            'nohp' => 'required|string|max:20',
            'otp_code' => $bypassOtp ? 'nullable|string|size:6' : 'required|string|size:6',
            'device_name' => 'required|string',
        ]);

        $user = User::where('nohp', $validated['nohp'])->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'nohp' => ['Akun dengan nomor tersebut tidak ditemukan.'],
            ]);
        }

        if (!in_array($user->role, ['guru', 'orangtua'])) {
            throw ValidationException::withMessages([
                'nohp' => ['Akses mobile hanya untuk Guru dan Orang Tua.'],
            ]);
        }

        if (!$bypassOtp || !empty($validated['otp_code'])) {
            $this->otpService->validate(
                $validated['nohp'],
                $validated['otp_code'],
                OtpType::Login
            );
        }

        $token = $user->createToken($validated['device_name'])->plainTextToken;

        return response()->json([
            'success' => true,
            'data' => [
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                    'nohp' => $user->nohp,
                ],
            ],
            'message' => 'Login berhasil',
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil',
        ]);
    }

    public function user(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'nohp' => $user->nohp,
            ],
        ]);
    }
}
