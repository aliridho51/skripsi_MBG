<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KritikSaran;
use Illuminate\Http\Request;

class KritikSaranController extends Controller
{
    public function index()
    {
        // Ambil semua feedback beserta data sekolah dan distribusi
        $feedbacks = KritikSaran::with(['sekolah', 'distribusi'])->orderByDesc('created_at')->get();
        
        // Statistik sederhana
        $totalFeedback = $feedbacks->count();
        $rataRataRating = $totalFeedback > 0 ? $feedbacks->avg('rating') : 0;
        
        // Menghitung jumlah per kategori
        $kategoriCount = $feedbacks->groupBy('kategori')->map->count();

        return view('admin.kritik_saran.index', compact('feedbacks', 'totalFeedback', 'rataRataRating', 'kategoriCount'));
    }
}
