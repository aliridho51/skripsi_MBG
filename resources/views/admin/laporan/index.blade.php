@extends('layouts.admin')

@section('header_title', 'Laporan Penyaluran Harian')

@section('content')
    {{-- ===== FILTER & EKSPOR ===== --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">

        {{-- Filter Rentang Tanggal --}}
        <div class="bg-white p-6 rounded-lg shadow border-l-4 border-blue-500">
            <label class="text-sm font-bold text-gray-500 uppercase block mb-3">
                <i class="fas fa-calendar-alt mr-1 text-blue-500"></i> Filter Rentang Tanggal
            </label>
            <form action="{{ route('admin.laporan.index') }}" method="GET">
                <div class="flex flex-col sm:flex-row gap-2">
                    <div class="flex-1">
                        <label class="text-xs text-gray-400 mb-1 block">Dari</label>
                        <input
                            type="date"
                            name="tanggal_mulai"
                            value="{{ $tanggal_mulai ?? '' }}"
                            class="border rounded px-3 py-2 text-sm w-full focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div class="flex-1">
                        <label class="text-xs text-gray-400 mb-1 block">Sampai</label>
                        <input
                            type="date"
                            name="tanggal_akhir"
                            value="{{ $tanggal_akhir ?? '' }}"
                            class="border rounded px-3 py-2 text-sm w-full focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div class="flex items-end gap-2">
                        <button type="submit"
                            class="bg-slate-800 text-white px-4 py-2 rounded text-sm hover:bg-slate-700 whitespace-nowrap">
                            <i class="fas fa-filter mr-1"></i> Filter
                        </button>
                        @if($tanggal_mulai || $tanggal_akhir)
                            <a href="{{ route('admin.laporan.index') }}"
                                class="bg-gray-200 text-gray-700 px-4 py-2 rounded text-sm hover:bg-gray-300 whitespace-nowrap">
                                <i class="fas fa-times mr-1"></i> Reset
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>

        {{-- Ekspor Data --}}
        <div class="bg-white p-6 rounded-lg shadow border-l-4 border-green-500 flex items-center justify-between">
            <div>
                <h4 class="font-bold text-gray-800"><i class="fas fa-download mr-2 text-green-500"></i>Ekspor Data</h4>
                <p class="text-xs text-gray-500 mt-1">Unduh riwayat penyaluran format PDF / Excel</p>
                @if($tanggal_mulai || $tanggal_akhir)
                    <p class="text-xs text-blue-600 mt-1">
                        <i class="fas fa-info-circle mr-1"></i>
                        Mengekspor data sesuai filter aktif
                    </p>
                @else
                    <p class="text-xs text-gray-400 mt-1">Semua data (tanpa filter)</p>
                @endif
            </div>
            <div class="flex flex-col sm:flex-row gap-2">
                {{-- Tombol Excel --}}
                <a href="{{ route('admin.laporan.export.excel', array_filter(['tanggal_mulai' => $tanggal_mulai, 'tanggal_akhir' => $tanggal_akhir])) }}"
                    class="flex items-center bg-green-600 text-white px-4 py-2 rounded text-sm hover:bg-green-700 transition-colors">
                    <i class="fas fa-file-excel mr-2"></i>Excel
                </a>
                {{-- Tombol PDF --}}
                <a href="{{ route('admin.laporan.export.pdf', array_filter(['tanggal_mulai' => $tanggal_mulai, 'tanggal_akhir' => $tanggal_akhir])) }}"
                    class="flex items-center bg-red-600 text-white px-4 py-2 rounded text-sm hover:bg-red-700 transition-colors">
                    <i class="fas fa-file-pdf mr-2"></i>PDF
                </a>
            </div>
        </div>
    </div>

    {{-- ===== INFO RINGKASAN ===== --}}
    @php
        $totalDisalurkan = $laporan->sum('total_disalurkan');
        $totalTarget     = $laporan->sum('target');
        $persenTotal     = $totalTarget > 0 ? round(($totalDisalurkan / $totalTarget) * 100, 1) : 0;
    @endphp
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4 border-t-4 border-blue-500 flex items-center gap-4">
            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                <i class="fas fa-utensils text-blue-600 text-xl"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase font-semibold">Total Realisasi</p>
                <p class="text-2xl font-bold text-blue-700">{{ number_format($totalDisalurkan) }}</p>
                <p class="text-xs text-gray-400">Porsi Disalurkan</p>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-4 border-t-4 border-green-500 flex items-center gap-4">
            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                <i class="fas fa-bullseye text-green-600 text-xl"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase font-semibold">Total Target</p>
                <p class="text-2xl font-bold text-green-700">{{ number_format($totalTarget) }}</p>
                <p class="text-xs text-gray-400">Porsi Target</p>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-4 border-t-4 border-orange-500 flex items-center gap-4">
            <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                <i class="fas fa-chart-pie text-orange-600 text-xl"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase font-semibold">Capaian</p>
                <p class="text-2xl font-bold text-orange-700">{{ $persenTotal }}%</p>
                <p class="text-xs text-gray-400">Dari Target</p>
            </div>
        </div>
    </div>

    {{-- ===== TABEL LAPORAN HARIAN ===== --}}
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h3 class="font-bold text-gray-800 text-lg">
                <i class="fas fa-table mr-2 text-slate-600"></i>
                Riwayat Penyaluran Harian
            </h3>
            @if($tanggal_mulai || $tanggal_akhir)
                <span class="text-xs bg-blue-100 text-blue-700 px-3 py-1 rounded-full font-medium">
                    <i class="fas fa-filter mr-1"></i>
                    Filter aktif: {{ $laporan->count() }} hari
                </span>
            @else
                <span class="text-xs bg-gray-100 text-gray-600 px-3 py-1 rounded-full">
                    {{ $laporan->count() }} hari
                </span>
            @endif
        </div>
        <div class="overflow-x-auto">
            <table class="w-full whitespace-nowrap text-left">
                <thead class="bg-gray-50 text-gray-600 text-sm uppercase font-semibold">
                    <tr>
                        <th class="px-6 py-3">No</th>
                        <th class="px-6 py-3">Tanggal</th>
                        <th class="px-6 py-3">Realisasi (Porsi)</th>
                        <th class="px-6 py-3">Target (Porsi)</th>
                        <th class="px-6 py-3">Persentase</th>
                        <th class="px-6 py-3">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 text-gray-700">
                    @forelse($laporan as $index => $lap)
                        @php $persen = $lap['target'] > 0 ? ($lap['total_disalurkan'] / $lap['target']) * 100 : 0; @endphp
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-gray-400 text-sm">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 font-medium">
                                <i class="fas fa-calendar-day mr-1 text-blue-400"></i>
                                {{ \Carbon\Carbon::parse($lap['tanggal'])->translatedFormat('d F Y') }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-semibold text-blue-700">{{ number_format($lap['total_disalurkan']) }}</span>
                                <span class="text-xs text-gray-400 ml-1">porsi</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-semibold text-gray-700">{{ number_format($lap['target']) }}</span>
                                <span class="text-xs text-gray-400 ml-1">porsi</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <span class="font-semibold {{ $persen >= 100 ? 'text-green-600' : ($persen >= 75 ? 'text-yellow-600' : 'text-red-500') }}">
                                        {{ round($persen, 1) }}%
                                    </span>
                                    <div class="w-20 bg-gray-200 rounded-full h-2">
                                        <div class="h-2 rounded-full {{ $persen >= 100 ? 'bg-green-500' : ($persen >= 75 ? 'bg-yellow-400' : 'bg-red-400') }}"
                                            style="width: {{ min($persen, 100) }}%"></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 inline-flex text-xs font-semibold rounded-full
                                    {{ $lap['status'] == 'Selesai' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    <i class="fas {{ $lap['status'] == 'Selesai' ? 'fa-check-circle' : 'fa-exclamation-circle' }} mr-1"></i>
                                    {{ $lap['status'] }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center text-gray-400">
                                <i class="fas fa-chart-bar text-4xl mb-3 block text-gray-300"></i>
                                <p class="text-base font-medium">Belum ada data laporan distribusi</p>
                                @if($tanggal_mulai || $tanggal_akhir)
                                    <p class="text-sm mt-1">Coba ubah rentang tanggal filter</p>
                                    <a href="{{ route('admin.laporan.index') }}"
                                        class="mt-3 inline-block text-blue-600 hover:underline text-sm">
                                        <i class="fas fa-times-circle mr-1"></i> Hapus filter
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection