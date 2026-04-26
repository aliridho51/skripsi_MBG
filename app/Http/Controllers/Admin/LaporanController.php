<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Distribusi;

class LaporanController extends Controller
{
    public function index()
    {
        $laporan = Distribusi::with(['sekolah', 'petugas.user'])
            ->orderByDesc('tanggal')
            ->get()
            ->groupBy(fn($d) => \Carbon\Carbon::parse($d->tanggal)->format('Y-m-d'))
            ->map(function ($items, $tanggal) {
                return [
                    'tanggal'          => $tanggal,
                    'total_disalurkan' => $items->sum('porsi_diterima'),
                    'target'           => $items->sum('target_porsi'),
                    'status'           => $items->every(fn($i) => $i->status_pengiriman === 'Selesai')
                                            ? 'Selesai' : 'Ada Kendala',
                ];
            })
            ->values();

        return view('admin.laporan.index', compact('laporan'));
    }
}