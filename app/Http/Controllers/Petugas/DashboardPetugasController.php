<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Distribusi;
use App\Models\Petugas;
use Illuminate\Support\Facades\Auth;

class DashboardPetugasController extends Controller
{
    public function index()
    {
        $user    = Auth::user();
        $petugas = Petugas::where('user_id', $user->id)->first();

        // Ambil tugas aktif hari ini jika ada
        $tugas_aktif = null;
        if ($petugas) {
            $tugas_aktif = Distribusi::with('sekolah')
                ->where('petugas_id', $petugas->id)
                ->whereDate('tanggal', today())
                ->whereIn('status_pengiriman', ['Belum Dikirim', 'Dalam Perjalanan'])
                ->first();
        }

        return view('petugas.dashboard', compact('tugas_aktif', 'petugas'));
    }

    public function riwayat()
    {
        $user    = Auth::user();
        $petugas = Petugas::where('user_id', $user->id)->first();

        $riwayat_pengiriman = collect();
        if ($petugas) {
            $riwayat_pengiriman = Distribusi::with('sekolah')
                ->where('petugas_id', $petugas->id)
                ->where('status_pengiriman', 'Selesai')
                ->orderByDesc('tanggal')
                ->get();
        }

        return view('petugas.riwayat', compact('riwayat_pengiriman'));
    }

    public function profil()
    {
        $user    = Auth::user();
        $petugas = Petugas::where('user_id', $user->id)->first();

        return view('petugas.profil', compact('user', 'petugas'));
    }
}