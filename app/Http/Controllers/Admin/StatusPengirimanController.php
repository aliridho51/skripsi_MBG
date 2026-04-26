<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Distribusi;

class StatusPengirimanController extends Controller
{
    public function index()
    {
        $status_pengiriman = Distribusi::with(['sekolah', 'petugas.user'])
            ->orderByDesc('tanggal')
            ->get();

        return view('admin.status.index', compact('status_pengiriman'));
    }
}