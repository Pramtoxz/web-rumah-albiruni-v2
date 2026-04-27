<?php

namespace App\Http\Controllers\Api\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $guru = $user->guru()->with('kelas')->first();

        if (!$guru) {
            return response()->json([
                'success' => false,
                'message' => 'Data guru tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $user->id,
                'name' => $guru->nama_lengkap ?? $user->name,
                'email' => $user->email,
                'nohp' => $user->nohp,
                'role' => $user->role,
                'foto_profil' => $guru->foto_guru ? url('assets/images/foto_guru/' . $guru->foto_guru) : null,
                'alamat' => $guru->alamat,
                'nip' => $guru->nip,
                'pendidikan_terakhir' => $guru->pendidikan_terakhir,
                'kelas' => $guru->kelas ? [
                    'id' => $guru->kelas->id,
                    'nama_kelas' => $guru->kelas->nama_kelas,
                ] : null,
                'created_at' => $user->created_at->toISOString(),
            ],
            'message' => 'Profile retrieved successfully',
        ]);
    }

    public function update(Request $request)
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
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
            'nohp' => 'sometimes|string|max:20|unique:users,nohp,' . $user->id,
            'nama_lengkap' => 'sometimes|string|max:255',
            'tempat_lahir' => 'sometimes|string|max:255',
            'tanggal_lahir' => 'sometimes|date',
            'jenis_kelamin' => 'sometimes|in:L,P',
            'alamat' => 'sometimes|string',
            'pendidikan_terakhir' => 'sometimes|string|max:255',
            'foto_guru' => 'sometimes|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if (isset($validated['name']) || isset($validated['email']) || isset($validated['nohp'])) {
            $user->update([
                'name' => $validated['name'] ?? $user->name,
                'email' => $validated['email'] ?? $user->email,
                'nohp' => $validated['nohp'] ?? $user->nohp,
            ]);
        }

        $guruData = [];
        foreach (['nama_lengkap', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'alamat', 'pendidikan_terakhir'] as $field) {
            if (isset($validated[$field])) {
                $guruData[$field] = $validated[$field];
            }
        }

        if ($request->hasFile('foto_guru')) {
            if ($guru->foto_guru && Storage::disk('public')->exists('foto_guru/' . $guru->foto_guru)) {
                Storage::disk('public')->delete('foto_guru/' . $guru->foto_guru);
            }

            $file = $request->file('foto_guru');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('foto_guru', $filename, 'public');
            $guruData['foto_guru'] = $filename;
        }

        if (!empty($guruData)) {
            $guru->update($guruData);
        }

        $guru->refresh();

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $user->id,
                'name' => $guru->nama_lengkap ?? $user->name,
                'email' => $user->email,
                'nohp' => $user->nohp,
                'role' => $user->role,
                'foto_profil' => $guru->foto_guru ? url('assets/images/foto_guru/' . $guru->foto_guru) : null,
                'alamat' => $guru->alamat,
                'nip' => $guru->nip,
                'pendidikan_terakhir' => $guru->pendidikan_terakhir,
            ],
            'message' => 'Profile berhasil diupdate',
        ]);
    }
}
