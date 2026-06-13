<?php

namespace App\Http\Controllers\Sekolah;

use App\Http\Controllers\Controller;
use App\Models\Distribusi;
use App\Models\KritikSaran;
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
            // Prioritaskan mencari yang masih aktif (Belum Dikirim / Dalam Perjalanan)
            $distribusi_hari_ini = Distribusi::with('petugas.user')
                ->where('sekolah_id', $sekolah->id)
                ->whereIn('status_pengiriman', ['Belum Dikirim', 'Dalam Perjalanan'])
                ->orderByDesc('tanggal')
                ->first();

            // Jika tidak ada yang aktif, ambil yang terbaru (misal sudah Selesai)
            if (!$distribusi_hari_ini) {
                $distribusi_hari_ini = Distribusi::with('petugas.user')
                    ->where('sekolah_id', $sekolah->id)
                    ->orderByDesc('created_at')
                    ->first();
            }
        }

        $info_hari_ini = [
            'tanggal'       => $distribusi_hari_ini 
                                    ? \Carbon\Carbon::parse($distribusi_hari_ini->tanggal)->translatedFormat('d F Y') 
                                    : now()->translatedFormat('d F Y'),
            'kuota'         => $distribusi_hari_ini?->target_porsi ?? 0,
            'petugas'       => $distribusi_hari_ini?->petugas?->user?->name ?? '-',
            'estimasi_tiba' => $distribusi_hari_ini?->waktu_tiba
                                    ? date('H:i', strtotime($distribusi_hari_ini->waktu_tiba)) . ' WIB'
                                    : '-',
            'status'        => $distribusi_hari_ini?->status_pengiriman ?? 'Belum Ada Jadwal',
        ];

        return view('sekolah.dashboard', compact('info_hari_ini', 'sekolah', 'distribusi_hari_ini'));
    }

    public function konfirmasi()
    {
        $user    = Auth::user();
        $sekolah = Sekolah::where('user_id', $user->id)->first();

        $distribusi = null;
        if ($sekolah) {
            // Cari pengiriman yang aktif untuk dikonfirmasi
            $distribusi = Distribusi::where('sekolah_id', $sekolah->id)
                ->whereIn('status_pengiriman', ['Belum Dikirim', 'Dalam Perjalanan'])
                ->orderByDesc('tanggal')
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
            'foto_bukti'      => 'required|image|mimes:jpeg,png,jpg|max:20480',
        ]);

        $user    = Auth::user();
        $sekolah = Sekolah::where('user_id', $user->id)->first();

        if (!$sekolah) {
            return redirect()->back()->with('error', 'Data sekolah tidak ditemukan.');
        }

        $distribusi = Distribusi::where('sekolah_id', $sekolah->id)
            ->where('status_pengiriman', 'Dalam Perjalanan')
            ->orderByDesc('tanggal')
            ->first();

        if (!$distribusi) {
            return redirect()->back()->with('error', 'Tidak ada pengiriman yang sedang dalam perjalanan untuk dikonfirmasi saat ini.');
        }

        if ($request->hasFile('foto_bukti')) {
            $file = $request->file('foto_bukti');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/bukti_terima'), $filename);
            $distribusi->foto_bukti = 'uploads/bukti_terima/' . $filename;
            // Simpan juga sebagai base64 agar tidak hilang saat redeploy di Railway
            $distribusi->foto_bukti_data = 'data:' . $file->getClientMimeType() . ';base64,' . base64_encode(file_get_contents(public_path($distribusi->foto_bukti)));
        }

        $distribusi->porsi_diterima = $request->jumlah_diterima;
        $distribusi->status_pengiriman = 'Selesai';
        $distribusi->waktu_tiba = \Carbon\Carbon::parse($distribusi->tanggal)->setTimeFrom(now());
        $distribusi->save();

        return redirect()->route('sekolah.riwayat')->with('success', 'Konfirmasi penerimaan berhasil disimpan.');
    }

    /**
     * Tracking page: GoFood-style delivery tracking with timeline
     */
    public function tracking()
    {
        $user    = Auth::user();
        $sekolah = Sekolah::where('user_id', $user->id)->first();

        $distribusi = null;
        if ($sekolah) {
            // Prioritaskan mencari pengiriman yang masih berjalan
            $distribusi = Distribusi::with('petugas.user')
                ->where('sekolah_id', $sekolah->id)
                ->whereIn('status_pengiriman', ['Belum Dikirim', 'Dalam Perjalanan'])
                ->orderByDesc('tanggal')
                ->first();

            // Jika tidak ada yang aktif, ambil riwayat terbaru
            if (!$distribusi) {
                $distribusi = Distribusi::with('petugas.user')
                    ->where('sekolah_id', $sekolah->id)
                    ->orderByDesc('created_at')
                    ->first();
            }
        }

        $id_pengiriman = $distribusi
            ? 'TRX-' . \Carbon\Carbon::parse($distribusi->tanggal)->format('Ymd') . '-' . str_pad($distribusi->id, 3, '0', STR_PAD_LEFT)
            : '-';

        $estimasi_tiba = $distribusi?->waktu_tiba
            ? date('H:i', strtotime($distribusi->waktu_tiba)) . ' WIB'
            : ($distribusi ? '10:00 - 11:00 WIB' : '-');

        return view('sekolah.tracking', compact('distribusi', 'id_pengiriman', 'estimasi_tiba'));
    }

    /**
     * Kritik & Saran page: feedback form + history
     */
    public function kritikSaran()
    {
        $user    = Auth::user();
        $sekolah = Sekolah::where('user_id', $user->id)->first();

        $distribusi_list = collect();
        $riwayat_feedback = collect();

        if ($sekolah) {
            // Get recent distribusi for optional linking
            $distribusi_list = Distribusi::where('sekolah_id', $sekolah->id)
                ->orderByDesc('tanggal')
                ->limit(10)
                ->get();

            // Get past feedback
            $riwayat_feedback = KritikSaran::with('distribusi')
                ->where('sekolah_id', $sekolah->id)
                ->orderByDesc('created_at')
                ->get();
        }

        return view('sekolah.kritik_saran', compact('distribusi_list', 'riwayat_feedback'));
    }

    /**
     * Store Kritik & Saran
     */
    public function storeKritikSaran(Request $request)
    {
        $request->validate([
            'kategori'      => 'required|in:Kualitas Makanan,Ketepatan Waktu',
            'komentar'      => 'required|string|max:1000',
            'distribusi_id' => 'nullable|exists:distribusi,id',
        ]);

        $user    = Auth::user();
        $sekolah = Sekolah::where('user_id', $user->id)->first();

        if (!$sekolah) {
            return redirect()->back()->with('error', 'Data sekolah tidak ditemukan.');
        }

        KritikSaran::create([
            'sekolah_id'    => $sekolah->id,
            'distribusi_id' => $request->distribusi_id ?: null,
            'kategori'      => $request->kategori,
            'rating'        => 5,
            'komentar'      => $request->komentar,
        ]);

        return redirect()->route('sekolah.kritik-saran')->with('success', 'Terima kasih! Umpan balik Anda telah berhasil dikirim.');
    }

    /**
     * Pengembalian Ompreng (Wadah Makanan)
     */
    public function pengembalianOmpreng()
    {
        $user    = Auth::user();
        $sekolah = Sekolah::where('user_id', $user->id)->first();

        $distribusi_selesai = collect();
        $riwayat_pengembalian = collect();

        if ($sekolah) {
            // Get recent distributions that have been completed but not yet returned
            $distribusi_selesai = Distribusi::where('sekolah_id', $sekolah->id)
                ->where('status_pengiriman', 'Selesai')
                ->whereDoesntHave('pengembalianOmpreng')
                ->orderByDesc('tanggal')
                ->get();

            // Get history of returns
            $riwayat_pengembalian = \App\Models\PengembalianOmpreng::with('distribusi')
                ->where('sekolah_id', $sekolah->id)
                ->orderByDesc('created_at')
                ->get();
        }

        return view('sekolah.pengembalian_ompreng', compact('distribusi_selesai', 'riwayat_pengembalian'));
    }

    public function storePengembalianOmpreng(Request $request)
    {
        $request->validate([
            'distribusi_id'  => 'required|exists:distribusi,id',
            'jumlah_kembali' => 'required|integer|min:0',
            'jumlah_rusak'   => 'required|integer|min:0',
            'kondisi'        => 'required|in:Baik Semua,Sebagian Rusak,Banyak Rusak',
            'catatan'        => 'nullable|string|max:1000',
            'foto_kondisi'   => 'nullable|image|mimes:jpeg,png,jpg|max:20480',
        ]);

        $user    = Auth::user();
        $sekolah = Sekolah::where('user_id', $user->id)->first();

        if (!$sekolah) {
            return redirect()->back()->with('error', 'Data sekolah tidak ditemukan.');
        }

        $distribusi = Distribusi::where('id', $request->distribusi_id)
            ->where('sekolah_id', $sekolah->id)
            ->first();

        if (!$distribusi) {
            return redirect()->back()->with('error', 'Data pengiriman tidak valid.');
        }

        $data = [
            'distribusi_id'  => $distribusi->id,
            'sekolah_id'     => $sekolah->id,
            'jumlah_dikirim' => $distribusi->porsi_diterima ?? $distribusi->target_porsi,
            'jumlah_kembali' => $request->jumlah_kembali,
            'jumlah_rusak'   => $request->jumlah_rusak,
            'kondisi'        => $request->kondisi,
            'status'         => 'Sudah Dikembalikan',
            'catatan'        => $request->catatan,
        ];

        if ($request->hasFile('foto_kondisi')) {
            $file = $request->file('foto_kondisi');
            $filename = time() . '_kondisi_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/ompreng'), $filename);
            $data['foto_kondisi'] = 'uploads/ompreng/' . $filename;
        }

        \App\Models\PengembalianOmpreng::create($data);

        return redirect()->route('sekolah.pengembalian-ompreng')->with('success', 'Data pengembalian ompreng berhasil disimpan.');
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