<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Distribusi;
use App\Models\Sekolah;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $total_sekolah      = Sekolah::count();
        $total_petugas      = User::where('role', 'petugas')->count();
        $total_distribusi   = Distribusi::count();
        $selesai_hari_ini   = Distribusi::whereDate('tanggal', today())
                                ->where('status_pengiriman', 'Selesai')->count();
        $target_hari_ini    = Distribusi::whereDate('tanggal', today())->count();
        $porsi_hari_ini     = Distribusi::whereDate('tanggal', today())->sum('porsi_diterima');
        $target_porsi       = Distribusi::whereDate('tanggal', today())->sum('target_porsi');
        $persentase_harian  = $target_porsi > 0 ? round(($porsi_hari_ini / $target_porsi) * 100, 1) : 0;

        $data = [
            'total_penerima'      => $total_sekolah,
            'tersalurkan_hari_ini'=> $porsi_hari_ini,
            'target_hari_ini'     => $target_porsi ?: $total_sekolah,
            'titik_distribusi'    => $total_sekolah,
            'persentase_harian'   => $persentase_harian,
        ];

        $recent_activities = Distribusi::with(['sekolah', 'petugas.user'])
            ->latest('updated_at')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('data', 'recent_activities'));
    }
}