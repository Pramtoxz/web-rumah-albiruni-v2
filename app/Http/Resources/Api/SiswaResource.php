<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SiswaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nama_lengkap' => $this->nama_lengkap,
            'nama_panggilan' => $this->nama_panggilan,
            'foto_siswa' => $this->foto_siswa,
            'kelas' => $this->whenLoaded('kelas', [
                'id' => $this->kelas?->id,
                'nama_kelas' => $this->kelas?->nama_kelas,
            ]),
        ];
    }
}
