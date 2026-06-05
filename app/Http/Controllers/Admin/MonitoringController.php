<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Distribusi;
use App\Models\Sekolah;
use App\Models\KritikSaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MonitoringController extends Controller
{
    public function index(Request $request)
    {
        $periode   = $request->get('periode', 'bulanan');
        $tahun     = $request->get('tahun', now()->year);
        $bulan     = $request->get('bulan', now()->month);

        // ─── CHART DATA ────────────────────────────────────────────────
        if ($periode === 'mingguan') {
            // 7 hari terakhir dari minggu yang dipilih
            $startDate = Carbon::now()->startOfWeek();
            $endDate   = Carbon::now()->endOfWeek();

            $chartLabels   = [];
            $chartTarget   = [];
            $chartTersalur = [];
            $chartKurang   = [];

            for ($d = $startDate->copy(); $d->lte($endDate); $d->addDay()) {
                $chartLabels[] = $d->translatedFormat('D, d M');
                $rows = Distribusi::whereDate('tanggal', $d->toDateString())->get();
                $target   = $rows->sum('target_porsi');
                $terima   = $rows->sum('porsi_diterima');
                $chartTarget[]   = $target;
                $chartTersalur[] = $terima;
                $chartKurang[]   = max(0, $target - $terima);
            }
        } else {
            // Bulanan — 12 bulan dalam tahun yang dipilih
            $chartLabels   = [];
            $chartTarget   = [];
            $chartTersalur = [];
            $chartKurang   = [];

            $namaBulan = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
            for ($m = 1; $m <= 12; $m++) {
                $chartLabels[]   = $namaBulan[$m - 1];
                $rows = Distribusi::whereYear('tanggal', $tahun)->whereMonth('tanggal', $m)->get();
                $target = $rows->sum('target_porsi');
                $terima = $rows->sum('porsi_diterima');
                $chartTarget[]   = $target;
                $chartTersalur[] = $terima;
                $chartKurang[]   = max(0, $target - $terima);
            }
        }

        // ─── STATISTIK RINGKASAN ───────────────────────────────────────
        $totalTarget      = Distribusi::sum('target_porsi');
        $totalTersalurkan = Distribusi::sum('porsi_diterima');
        $totalKekurangan  = max(0, $totalTarget - $totalTersalurkan);
        $persentaseTotal  = $totalTarget > 0 ? round(($totalTersalurkan / $totalTarget) * 100, 1) : 0;

        $totalSelesai     = Distribusi::where('status_pengiriman', 'Selesai')->count();
        $totalDalamPerjalanan = Distribusi::where('status_pengiriman', 'Dalam Perjalanan')->count();
        $totalBelumDikirim    = Distribusi::where('status_pengiriman', 'Belum Dikirim')->count();
        $totalAdaKendala      = Distribusi::where('status_pengiriman', 'Ada Kendala')->count();

        // ─── DATA PER SEKOLAH ──────────────────────────────────────────
        $perSekolah = Sekolah::withCount([
                'distribusi as total_pengiriman',
            ])
            ->withSum('distribusi as total_target', 'target_porsi')
            ->withSum('distribusi as total_diterima', 'porsi_diterima')
            ->get()
            ->map(function ($s) {
                $s->total_kekurangan = max(0, ($s->total_target ?? 0) - ($s->total_diterima ?? 0));
                $s->persen = ($s->total_target ?? 0) > 0
                    ? round((($s->total_diterima ?? 0) / $s->total_target) * 100, 1)
                    : 0;
                return $s;
            })
            ->sortByDesc('total_kekurangan');

        // ─── UMPAN BALIK TERBARU ───────────────────────────────────────
        $umpanBalik = KritikSaran::with('sekolah')
            ->latest()
            ->take(5)
            ->get();

        // ─── PIE CHART STATUS ──────────────────────────────────────────
        $statusData = [
            'Selesai'         => $totalSelesai,
            'Dalam Perjalanan'=> $totalDalamPerjalanan,
            'Belum Dikirim'   => $totalBelumDikirim,
            'Ada Kendala'     => $totalAdaKendala,
        ];

        $tahunList = Distribusi::selectRaw('YEAR(tanggal) as tahun')
            ->groupBy('tahun')
            ->orderByDesc('tahun')
            ->pluck('tahun');

        return view('admin.monitoring.index', compact(
            'periode', 'tahun', 'bulan',
            'chartLabels', 'chartTarget', 'chartTersalur', 'chartKurang',
            'totalTarget', 'totalTersalurkan', 'totalKekurangan', 'persentaseTotal',
            'totalSelesai', 'totalDalamPerjalanan', 'totalBelumDikirim', 'totalAdaKendala',
            'perSekolah', 'umpanBalik', 'statusData', 'tahunList'
        ));
    }

    /**
     * Endpoint AJAX untuk memuat ulang data chart tanpa reload penuh.
     */
    public function chartData(Request $request)
    {
        $periode = $request->get('periode', 'bulanan');
        $tahun   = $request->get('tahun', now()->year);

        if ($periode === 'mingguan') {
            $startDate = Carbon::now()->startOfWeek();
            $endDate   = Carbon::now()->endOfWeek();

            $labels = $target = $tersalur = $kurang = [];
            for ($d = $startDate->copy(); $d->lte($endDate); $d->addDay()) {
                $labels[]   = $d->translatedFormat('D, d M');
                $rows = Distribusi::whereDate('tanggal', $d->toDateString())->get();
                $t = $rows->sum('target_porsi');
                $r = $rows->sum('porsi_diterima');
                $target[]   = $t;
                $tersalur[] = $r;
                $kurang[]   = max(0, $t - $r);
            }
        } else {
            $namaBulan = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
            $labels = $target = $tersalur = $kurang = [];
            for ($m = 1; $m <= 12; $m++) {
                $labels[]   = $namaBulan[$m - 1];
                $rows = Distribusi::whereYear('tanggal', $tahun)->whereMonth('tanggal', $m)->get();
                $t = $rows->sum('target_porsi');
                $r = $rows->sum('porsi_diterima');
                $target[]   = $t;
                $tersalur[] = $r;
                $kurang[]   = max(0, $t - $r);
            }
        }

        return response()->json([
            'labels'   => $labels,
            'target'   => $target,
            'tersalur' => $tersalur,
            'kurang'   => $kurang,
        ]);
    }
}
