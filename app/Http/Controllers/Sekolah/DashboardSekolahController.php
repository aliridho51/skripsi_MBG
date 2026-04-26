<?php

namespace App\Http\Controllers\Sekolah;

use App\Http\Controllers\Controller;
use App\Models\Distribusi;
use App\Models\Sekolah;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DashboardSekolahController extends Controller
{
    public function index()
    {
        $user    = Auth::user();
        $sekolah = Sekolah::where('user_id', $user->id)->first();

        $distribusi_hari_ini = null;
        if ($sekolah) {
            $distribusi_hari_ini = Distribusi::with('petugas.user')
                ->where('sekolah_id', $sekolah->id)
                ->whereDate('tanggal', today())
                ->first();
        }

        $info_hari_ini = [
            'tanggal'       => now()->translatedFormat('d F Y'),
            'kuota'         => $distribusi_hari_ini?->target_porsi ?? 0,
            'petugas'       => $distribusi_hari_ini?->petugas?->user?->name ?? '-',
            'estimasi_tiba' => $distribusi_hari_ini?->waktu_tiba
                                    ? date('H:i', strtotime($distribusi_hari_ini->waktu_tiba)) . ' WIB'
                                    : '-',
            'status'        => $distribusi_hari_ini?->status_pengiriman ?? 'Belum Ada Jadwal',
        ];

        return view('sekolah.dashboard', compact('info_hari_ini', 'sekolah'));
    }

    public function konfirmasi()
    {
        $user    = Auth::user();
        $sekolah = Sekolah::where('user_id', $user->id)->first();

        $distribusi = null;
        if ($sekolah) {
            $distribusi = Distribusi::where('sekolah_id', $sekolah->id)
                ->whereDate('tanggal', today())
                ->first();
        }

        $data_pengiriman = [
            'id_pengiriman' => $distribusi ? 'TRX-' . \Carbon\Carbon::parse($distribusi->tanggal)->format('Ymd') . '-' . str_pad($distribusi->id, 3, '0', STR_PAD_LEFT) : '-',
            'target_porsi'  => $distribusi?->target_porsi ?? 0,
            'menu_hari_ini' => $distribusi?->menu_hari_ini ?? 'Belum ada informasi menu',
        ];

        return view('sekolah.konfirmasi', compact('data_pengiriman', 'distribusi'));
    }

    public function storeKonfirmasi(Request $request)
    {
        $request->validate([
            'jumlah_diterima' => 'required|numeric|min:0',
            'foto_bukti'      => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user    = Auth::user();
        $sekolah = Sekolah::where('user_id', $user->id)->first();

        if (!$sekolah) {
            return redirect()->back()->with('error', 'Data sekolah tidak ditemukan.');
        }

        $distribusi = Distribusi::where('sekolah_id', $sekolah->id)
            ->whereDate('tanggal', today())
            ->first();

        if (!$distribusi) {
            return redirect()->back()->with('error', 'Tidak ada jadwal pengiriman untuk hari ini.');
        }

        if ($request->hasFile('foto_bukti')) {
            $file = $request->file('foto_bukti');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/bukti_terima'), $filename);
            $distribusi->foto_bukti = 'uploads/bukti_terima/' . $filename;
        }

        $distribusi->porsi_diterima = $request->jumlah_diterima;
        $distribusi->status_pengiriman = 'Selesai';
        $distribusi->waktu_tiba = now();
        $distribusi->save();

        return redirect()->route('sekolah.riwayat')->with('success', 'Konfirmasi penerimaan berhasil disimpan.');
    }

    public function riwayat()
    {
        $user    = Auth::user();
        $sekolah = Sekolah::where('user_id', $user->id)->first();

        $riwayat = collect();
        if ($sekolah) {
            $riwayat = Distribusi::with('petugas.user')
                ->where('sekolah_id', $sekolah->id)
                ->where('status_pengiriman', 'Selesai')
                ->orderByDesc('tanggal')
                ->get();
        }

        return view('sekolah.riwayat', compact('riwayat'));
    }

    public function profil()
    {
        $user    = Auth::user();
        $sekolah = Sekolah::where('user_id', $user->id)->first();

        return view('sekolah.profil', compact('user', 'sekolah'));
    }
}