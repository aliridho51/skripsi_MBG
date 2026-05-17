@extends('layouts.petugas')

@section('header_title', 'Riwayat Pengiriman')

@section('content')
    <!-- Statistik Ringkas Riwayat -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="stat-card bg-white rounded-lg shadow p-6 flex border-l-4 border-slate-900 animate-fade-in-up delay-1">
            <div class="flex-1">
                <p class="text-sm text-gray-500 font-semibold uppercase tracking-wider">Total Riwayat Tugas</p>
                <p class="text-3xl font-bold text-gray-800 mt-2 number-animate delay-3">{{ count($riwayat_pengiriman) }} <span class="text-sm font-medium text-gray-400">Pengiriman Selesai</span></p>
            </div>
            <div class="text-slate-800 text-3xl flex items-center stat-card-icon">
                <i class="fas fa-clipboard-check"></i>
            </div>
        </div>

        <div class="stat-card bg-white rounded-lg shadow p-6 flex border-l-4 border-blue-600 animate-fade-in-up delay-2">
            <div class="flex-1">
                <p class="text-sm text-gray-500 font-semibold uppercase tracking-wider">Total Porsi Tersalurkan</p>
                @php
                    $total_porsi = $riwayat_pengiriman->sum('target_porsi');
                @endphp
                <p class="text-3xl font-bold text-gray-800 mt-2 number-animate delay-4">{{ number_format($total_porsi, 0, ',', '.') }} <span class="text-sm font-medium text-gray-400">Porsi</span></p>
            </div>
            <div class="text-blue-600 text-3xl flex items-center stat-card-icon">
                <i class="fas fa-boxes"></i>
            </div>
        </div>
    </div>

    <!-- Daftar Riwayat -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden animate-fade-in-up delay-3 card-hover">
        <div class="px-8 py-5 border-b border-gray-100 bg-slate-50 flex justify-between items-center">
            <h3 class="font-bold text-slate-800 text-lg">Catatan Distribusi Selesai</h3>
            <span class="text-xs bg-slate-200 text-slate-600 px-3 py-1 rounded-md font-bold uppercase tracking-widest">Update Otomatis</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 text-slate-500 text-xs uppercase font-bold tracking-widest border-b border-slate-100">
                    <tr>
                        <th class="px-8 py-4">Waktu & Tanggal</th>
                        <th class="px-8 py-4">Sekolah Tujuan</th>
                        <th class="px-8 py-4 text-center">Jumlah Muatan</th>
                        <th class="px-8 py-4">Penerima (Sekolah)</th>
                        <th class="px-8 py-4">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($riwayat_pengiriman as $index => $item)
                    <tr class="table-row-animate animate-fade-in-up" style="animation-delay: {{ 0.4 + ($index * 0.08) }}s;">
                        <td class="px-8 py-5">
                            <div class="text-sm font-bold text-slate-800">{{ $item->updated_at->format('H:i') }} WIB</div>
                            <div class="text-[11px] text-slate-400 font-medium uppercase mt-0.5">{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}</div>
                        </td>
                        <td class="px-8 py-5">
                            <div class="text-sm font-black text-blue-800">{{ $item->sekolah->nama_sekolah ?? '-' }}</div>
                            <div class="text-[10px] text-slate-400 italic">Distribusi Makanan Sehat</div>
                        </td>
                        <td class="px-8 py-5 text-center">
                            <span class="bg-slate-100 text-slate-700 font-bold px-2 py-1 rounded border border-slate-200 text-xs">
                                {{ $item->target_porsi }} Porsi
                            </span>
                        </td>
                        <td class="px-8 py-5">
                            <div class="flex items-center text-sm font-bold text-slate-700">
                                <i class="fas fa-user-check text-blue-500 mr-2"></i>
                                {{ $item->sekolah->penanggung_jawab ?? '-' }}
                            </div>
                        </td>
                        <td class="px-8 py-5">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-tighter bg-green-100 text-green-700 border border-green-200 shadow-sm">
                                <i class="fas fa-check-circle mr-1.5 text-xs"></i> SELESAI
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="bg-slate-50/50 p-4 border-t border-slate-100 italic">
            <p class="text-[11px] text-slate-400 text-center">Seluruh data riwayat pengiriman disimpan secara permanen di server pusat sebagai bukti autentik penyaluran.</p>
        </div>
    </div>
@endsection
