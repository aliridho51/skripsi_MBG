<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Distribusi;
use App\Exports\LaporanExport;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    /**
     * Build laporan data grouped by day, with optional date filter.
     */
    private function buildLaporan($tanggal_mulai = null, $tanggal_akhir = null)
    {
        $query = Distribusi::with(['sekolah', 'petugas.user'])->orderBy('tanggal');

        if ($tanggal_mulai && $tanggal_akhir) {
            $query->whereBetween('tanggal', [$tanggal_mulai, $tanggal_akhir]);
        } elseif ($tanggal_mulai) {
            $query->whereDate('tanggal', $tanggal_mulai);
        }

        return $query->get()
            ->groupBy(fn($d) => Carbon::parse($d->tanggal)->format('Y-m-d'))
            ->map(function ($items, $tanggal) {
                return [
                    'tanggal'          => $tanggal,
                    'total_disalurkan' => $items->sum('porsi_diterima'),
                    'target'           => $items->sum('target_porsi'),
                    'jumlah_sekolah'   => $items->count(),
                    'status'           => $items->every(fn($i) => $i->status_pengiriman === 'Selesai')
                                            ? 'Selesai' : 'Ada Kendala',
                ];
            })
            ->sortByDesc('tanggal')
            ->values();
    }

    public function index(Request $request)
    {
        $tanggal_mulai = $request->input('tanggal_mulai');
        $tanggal_akhir = $request->input('tanggal_akhir');

        $laporan = $this->buildLaporan($tanggal_mulai, $tanggal_akhir);

        return view('admin.laporan.index', compact('laporan', 'tanggal_mulai', 'tanggal_akhir'));
    }

    public function exportExcel(Request $request)
    {
        $tanggal_mulai = $request->input('tanggal_mulai');
        $tanggal_akhir = $request->input('tanggal_akhir');

        $suffix = '';
        if ($tanggal_mulai && $tanggal_akhir) {
            $suffix = '_' . $tanggal_mulai . '_sd_' . $tanggal_akhir;
        } elseif ($tanggal_mulai) {
            $suffix = '_' . $tanggal_mulai;
        } else {
            $suffix = '_semua_data';
        }

        $filename = 'laporan_mbg' . $suffix . '.xlsx';

        return Excel::download(new LaporanExport($tanggal_mulai, $tanggal_akhir), $filename);
    }

    public function exportPdf(Request $request)
    {
        $tanggal_mulai = $request->input('tanggal_mulai');
        $tanggal_akhir = $request->input('tanggal_akhir');

        $laporan = $this->buildLaporan($tanggal_mulai, $tanggal_akhir);

        $pdf = Pdf::loadView('admin.laporan.pdf', compact('laporan', 'tanggal_mulai', 'tanggal_akhir'))
            ->setPaper('A4', 'portrait');

        $suffix = '';
        if ($tanggal_mulai && $tanggal_akhir) {
            $suffix = '_' . $tanggal_mulai . '_sd_' . $tanggal_akhir;
        } elseif ($tanggal_mulai) {
            $suffix = '_' . $tanggal_mulai;
        } else {
            $suffix = '_semua_data';
        }

        $filename = 'laporan_mbg' . $suffix . '.pdf';

        return $pdf->download($filename);
    }
}