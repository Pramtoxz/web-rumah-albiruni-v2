<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DailyReportResource extends JsonResource
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
            'siswa_id' => $this->siswa_id,
            'tanggal' => $this->tanggal?->format('Y-m-d'),
            'jam_masuk' => $this->jam_masuk,
            'jam_pulang' => $this->jam_pulang,
            'suhu_tubuh' => $this->suhu_tubuh,
            'kondisi_umum' => $this->kondisi_umum,
            'catatan_kesehatan' => $this->catatan_kesehatan,
            'makan_pagi' => $this->makan_pagi,
            'makan_siang' => $this->makan_siang,
            'makan_snack' => $this->makan_snack,
            'catatan_makan' => $this->catatan_makan,
            'tidur_siang_mulai' => $this->tidur_siang_mulai,
            'tidur_siang_selesai' => $this->tidur_siang_selesai,
            'kualitas_tidur' => $this->kualitas_tidur,
            'bab' => $this->bab,
            'bak' => $this->bak,
            'catatan_eliminasi' => $this->catatan_eliminasi,
            'kegiatan_pembelajaran' => $this->kegiatan_pembelajaran,
            'perkembangan_anak' => $this->perkembangan_anak,
            'catatan_guru' => $this->catatan_guru,
            'foto_kegiatan' => $this->foto_kegiatan,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
            'siswa' => new SiswaResource($this->whenLoaded('siswa')),
            'emosis' => $this->whenLoaded('emosis'),
        ];
    }
}
