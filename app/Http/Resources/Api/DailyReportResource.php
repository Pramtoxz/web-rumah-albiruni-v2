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
            
            // Mood & Activity
            'mood' => $this->mood,
            'activity' => $this->activity,
            
            // Menu Makanan dengan status
            'sarapan_pagi' => $this->sarapan_pagi,
            'sarapan_status' => $this->sarapan_status,
            'makan_siang' => $this->makan_siang,
            'makan_siang_status' => $this->makan_siang_status,
            'snack_sore' => $this->snack_sore,
            'snack_status' => $this->snack_status,
            
            // Minum
            'minum_air_putih' => $this->minum_air_putih,
            'minum_susu' => $this->minum_susu,
            
            // Tidur & Toilet dengan frekuensi
            'tidur_siang' => $this->tidur_siang,
            'tidur_siang_durasi' => $this->tidur_siang_durasi,
            'bak' => $this->bak,
            'bak_frekuensi' => $this->bak_frekuensi,
            'bab' => $this->bab,
            'bab_frekuensi' => $this->bab_frekuensi,
            
            // Kebutuhan & Catatan
            'kebutuhan_besok' => $this->kebutuhan_besok,
            'catatan_khusus' => $this->catatan_khusus,
            'catatan_insiden' => $this->catatan_insiden,
            
            // Foto
            'foto_kegiatan' => $this->foto_kegiatan,
            
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
            
            // Relations
            'siswa' => new SiswaResource($this->whenLoaded('siswa')),
            'emosis' => $this->whenLoaded('emosis'),
        ];
    }
}
