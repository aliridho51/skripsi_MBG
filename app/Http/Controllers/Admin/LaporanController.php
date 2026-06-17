<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Distribusi;
use Carbon\Carbon;
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

        $filename = 'laporan_mbg' . $suffix . '.csv';

        $laporan = $this->buildLaporan($tanggal_mulai, $tanggal_akhir);

        return response()->streamDownload(function() use ($laporan) {
            $file = fopen('php://output', 'w');
            // Add BOM to fix UTF-8 in Excel
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            // Force Excel to parse CSV columns by comma regardless of system locale
            fwrite($file, "sep=,\r\n");

            // Headings
            fputcsv($file, [
                'No',
                'Tanggal',
                'Realisasi (Porsi)',
                'Target (Porsi)',
                'Persentase (%)',
                'Jumlah Sekolah',
                'Status'
            ]);

            $no = 1;
            foreach ($laporan as $row) {
                $persen = $row['target'] > 0 ? round(($row['total_disalurkan'] / $row['target']) * 100, 1) : 0;
                fputcsv($file, [
                    $no++,
                    Carbon::parse($row['tanggal'])->translatedFormat('d F Y'),
                    $row['total_disalurkan'],
                    $row['target'],
                    $persen . '%',
                    $row['jumlah_sekolah'],
                    $row['status']
                ]);
            }
            fclose($file);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ]);
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