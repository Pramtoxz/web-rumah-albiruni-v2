<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DeviceToken;
use Illuminate\Http\Request;

class DeviceTokenController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'fcm_token' => 'required|string',
            'device_type' => 'required|in:android,ios',
            'device_name' => 'nullable|string|max:255',
        ]);

        $user = $request->user();

        $deviceToken = DeviceToken::updateOrCreate(
            [
                'user_id' => $user->id,
                'fcm_token' => $validated['fcm_token'],
            ],
            [
                'device_type' => $validated['device_type'],
                'device_name' => $validated['device_name'] ?? null,
                'is_active' => true,
            ]
        );

        return response()->json([
            'success' => true,
            'data' => $deviceToken,
            'message' => 'Device token registered successfully',
        ]);
    }

    public function destroy(Request $request)
    {
        $validated = $request->validate([
            'fcm_token' => 'required|string',
        ]);

        $user = $request->user();

        DeviceToken::where('user_id', $user->id)
            ->where('fcm_token', $validated['fcm_token'])
            ->update(['is_active' => false]);

        return response()->json([
            'success' => true,
            'message' => 'Device token unregistered successfully',
        ]);
    }
}
