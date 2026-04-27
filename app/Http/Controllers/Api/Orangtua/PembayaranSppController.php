<?php

namespace App\Http\Controllers\Api\Orangtua;

use App\Http\Controllers\Controller;
use App\Models\PembayaranSpp;
use App\Models\Siswa;
use App\Providers\WhatsAppGateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PembayaranSppController extends Controller
{
    private $whatsApp;

    public function __construct(WhatsAppGateway $whatsApp)
    {
        $this->whatsApp = $whatsApp;
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $siswa = Siswa::where('user_id', $user->id)->with('kelas')->first();

        if (!$siswa || !$siswa->status_siswa) {
            return response()->json([
                'success' => false,
                'message' => 'Data siswa belum disetujui atau belum terdaftar',
            ], 403);
        }

        $pembayaran = PembayaranSpp::where('siswa_id', $siswa->id)
            ->with(['kelas'])
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'siswa' => $siswa,
                'pembayaran' => $pembayaran,
            ],
        ]);
    }

    public function upload(Request $request, $id)
    {
        $validated = $request->validate([
            'bukti_bayar' => 'required|image|max:2048',
            'tanggal_bayar' => 'required|date',
            'metode_bayar' => 'required|in:transfer',
        ]);

        $user = $request->user();
        $siswa = Siswa::where('user_id', $user->id)->first();

        if (!$siswa) {
            return response()->json([
                'success' => false,
                'message' => 'Data siswa tidak ditemukan',
            ], 404);
        }

        $pembayaran = PembayaranSpp::where('siswa_id', $siswa->id)->findOrFail($id);

        if ($request->hasFile('bukti_bayar')) {
            if ($pembayaran->bukti_bayar) {
                $oldPath = public_path('assets/images/bukti_bayar/' . $pembayaran->bukti_bayar);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }

            $file = $request->file('bukti_bayar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('assets/images/bukti_bayar'), $filename);
            $validated['bukti_bayar'] = $filename;
        }

        $pembayaran->update([
            'bukti_bayar' => $validated['bukti_bayar'],
            'tanggal_bayar' => $validated['tanggal_bayar'],
            'metode_bayar' => $validated['metode_bayar'],
            'status_bayar' => 'menunggu_verifikasi',
        ]);

        try {
            $namaSiswa = $siswa->nama_lengkap;
            $nomorTujuan = '6281918285109';
            $pesan = "Ada Orang Tua dari " . $namaSiswa . " Baru Saja Upload Bukti Pembayaran Pada " . now()->timezone('Asia/Jakarta')->format('d-m-Y') . "\n Cek Sekarang Juga!!!";
            $this->whatsApp->sendText($nomorTujuan, $pesan);
        } catch (\Exception $waException) {
            Log::error('Gagal mengirim notifikasi WhatsApp: ' . $waException->getMessage());
        }

        return response()->json([
            'success' => true,
            'data' => $pembayaran,
            'message' => 'Bukti pembayaran berhasil diupload. Menunggu verifikasi admin.',
        ]);
    }
}
