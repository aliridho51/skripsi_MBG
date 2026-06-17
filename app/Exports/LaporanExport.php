<?php

namespace App\Exports;

use App\Models\Distribusi;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;

class LaporanExport implements FromCollection, WithHeadings, WithMapping, WithTitle, WithStyles, ShouldAutoSize
{
    protected $tanggal_mulai;
    protected $tanggal_akhir;

    public function __construct($tanggal_mulai = null, $tanggal_akhir = null)
    {
        $this->tanggal_mulai = $tanggal_mulai;
        $this->tanggal_akhir = $tanggal_akhir;
    }

    public function collection()
    {
        $query = Distribusi::with(['sekolah', 'petugas.user'])->orderBy('tanggal');

        if ($this->tanggal_mulai && $this->tanggal_akhir) {
            $query->whereBetween('tanggal', [$this->tanggal_mulai, $this->tanggal_akhir]);
        } elseif ($this->tanggal_mulai) {
            $query->whereDate('tanggal', $this->tanggal_mulai);
        }

        return $query->get()
            ->groupBy(fn($d) => Carbon::parse($d->tanggal)->format('Y-m-d'))
            ->map(function ($items, $tanggal) {
                return (object)[
                    'tanggal'          => $tanggal,
                    'total_disalurkan' => $items->sum('porsi_diterima'),
                    'target'           => $items->sum('target_porsi'),
                    'jumlah_sekolah'   => $items->count(),
                    'status'           => $items->every(fn($i) => $i->status_pengiriman === 'Selesai') ? 'Selesai' : 'Ada Kendala',
                ];
            })
            ->values();
    }

    public function headings(): array
    {
        return [
            'No',
            'Tanggal',
            'Realisasi (Porsi)',
            'Target (Porsi)',
            'Persentase (%)',
            'Jumlah Sekolah',
            'Status',
        ];
    }

    public function map($row): array
    {
        static $no = 0;
        $no++;

        $persen = $row->target > 0 ? round(($row->total_disalurkan / $row->target) * 100, 1) : 0;

        return [
            $no,
            Carbon::parse($row->tanggal)->translatedFormat('d F Y'),
            $row->total_disalurkan,
            $row->target,
            $persen . '%',
            $row->jumlah_sekolah,
            $row->status,
        ];
    }

    public function title(): string
    {
        return 'Laporan Penyaluran MBG';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Header row style
            1 => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF'], 'size' => 11],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF1E3A5F']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
        ];
    }
}
