<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Distribusi;
use App\Models\Petugas;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DashboardPetugasController extends Controller
{
    public function index()
    {
        $user    = Auth::user();
        $petugas = Petugas::where('user_id', $user->id)->first();

        // Ambil tugas aktif: prioritas hari ini, lalu yang belum selesai dari tanggal lain
        $tugas_aktif = null;
        if ($petugas) {
            // Coba cari tugas hari ini dulu
            $tugas_aktif = Distribusi::with('sekolah')
                ->where('petugas_id', $petugas->id)
                ->whereDate('tanggal', today())
                ->whereIn('status_pengiriman', ['Belum Dikirim', 'Dalam Perjalanan'])
                ->first();

            // Jika tidak ada tugas hari ini, cari tugas aktif terbaru dari tanggal lain
            if (!$tugas_aktif) {
                $tugas_aktif = Distribusi::with('sekolah')
                    ->where('petugas_id', $petugas->id)
                    ->whereIn('status_pengiriman', ['Belum Dikirim', 'Dalam Perjalanan'])
                    ->orderByDesc('tanggal')
                    ->first();
            }
        }

        return view('petugas.dashboard', compact('tugas_aktif', 'petugas'));
    }

    public function halamanKonfirmasi()
    {
        $user    = Auth::user();
        $petugas = Petugas::where('user_id', $user->id)->first();

        // Ambil tugas aktif: prioritas hari ini, lalu yang belum selesai dari tanggal lain
        $tugas_aktif = null;
        if ($petugas) {
            $tugas_aktif = Distribusi::with('sekolah')
                ->where('petugas_id', $petugas->id)
                ->whereDate('tanggal', today())
                ->whereIn('status_pengiriman', ['Belum Dikirim', 'Dalam Perjalanan'])
                ->first();

            if (!$tugas_aktif) {
                $tugas_aktif = Distribusi::with('sekolah')
                    ->where('petugas_id', $petugas->id)
                    ->whereIn('status_pengiriman', ['Belum Dikirim', 'Dalam Perjalanan'])
                    ->orderByDesc('tanggal')
                    ->first();
            }
        }

        return view('petugas.konfirmasi', compact('tugas_aktif', 'petugas'));
    }

    public function konfirmasi(Request $request, $id)
    {
        $user    = Auth::user();
        $petugas = Petugas::where('user_id', $user->id)->first();

        if (!$petugas) {
            return redirect()->back()->with('error', 'Data petugas tidak ditemukan.');
        }

        $distribusi = Distribusi::where('id', $id)
            ->where('petugas_id', $petugas->id)
            ->first();

        if (!$distribusi) {
            return redirect()->back()->with('error', 'Tugas tidak ditemukan.');
        }

        if ($distribusi->status_pengiriman === 'Dalam Perjalanan') {
            return redirect()->back()->with('error', 'Pengiriman sedang berlangsung, menunggu konfirmasi sekolah.');
        }

        $distribusi->status_pengiriman = 'Dalam Perjalanan';
        $distribusi->save();

        return redirect()->route('petugas.dashboard')->with('success', 'Status pengiriman berhasil diubah menjadi Dalam Perjalanan. Menunggu konfirmasi penerimaan dari pihak sekolah.');
    }

    public function laporKendala(Request $request, $id)
    {
        $request->validate([
            'keterangan_kendala' => 'required|string|max:1000'
        ]);

        $user    = Auth::user();
        $petugas = Petugas::where('user_id', $user->id)->first();

        if (!$petugas) {
            return redirect()->back()->with('error', 'Data petugas tidak ditemukan.');
        }

        $distribusi = Distribusi::where('id', $id)
            ->where('petugas_id', $petugas->id)
            ->first();

        if (!$distribusi) {
            return redirect()->back()->with('error', 'Tugas tidak ditemukan.');
        }

        $distribusi->keterangan_kendala = $request->keterangan_kendala;
        $distribusi->save();

        return redirect()->route('petugas.konfirmasi.halaman')->with('success', 'Laporan kendala berhasil dikirim. Sekolah dan Admin akan mengetahuinya.');
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