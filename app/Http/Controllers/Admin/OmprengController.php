<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PengembalianOmpreng;
use Illuminate\Http\Request;

class OmprengController extends Controller
{
    public function index()
    {
        // Ambil semua riwayat pengembalian ompreng
        $data_ompreng = PengembalianOmpreng::with(['sekolah', 'distribusi'])->orderByDesc('created_at')->get();
        
        // Hitung statistik kerugian/kerusakan
        $totalDikirim = $data_ompreng->sum('jumlah_dikirim');
        $totalKembali = $data_ompreng->sum('jumlah_kembali');
        $totalRusak = $data_ompreng->sum('jumlah_rusak');
        $totalHilang = max(0, $totalDikirim - $totalKembali - $totalRusak);

        return view('admin.ompreng.index', compact('data_ompreng', 'totalDikirim', 'totalKembali', 'totalRusak', 'totalHilang'));
    }
}
