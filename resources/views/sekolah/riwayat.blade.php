@extends('layouts.sekolah')
@section('header_title', 'Rekap Riwayat Penerimaan')
@section('content')
    <div class="bg-white rounded-lg shadow-sm border border-slate-200 animate-fade-in-up card-hover">
        <div class="p-4 border-b border-slate-200">
            @if(session('success'))
                <div class="alert-animate p-4 bg-green-50 border border-green-200 rounded-lg text-sm text-green-800">
                    <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                </div>
            @endif
        </div>
        <div class="overflow-x-auto">
            <table class="w-full whitespace-nowrap text-left">
                <thead class="bg-slate-50 text-slate-800 text-sm uppercase font-bold border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4">Tanggal</th>
                        <th class="px-6 py-4 text-center">Porsi Diterima</th>
                        <th class="px-6 py-4">Nama Petugas</th>
                        <th class="px-6 py-4">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @foreach($riwayat as $index => $r)
                        <tr class="table-row-animate animate-fade-in-up" style="animation-delay: {{ 0.1 + ($index * 0.08) }}s;">
                            <td class="px-6 py-4 font-medium">{{ \Carbon\Carbon::parse($r->tanggal)->translatedFormat('d F Y') }}</td>
                            <td class="px-6 py-4 text-center font-bold text-blue-600 number-animate" style="animation-delay: {{ 0.3 + ($index * 0.08) }}s;">{{ $r->porsi_diterima }} / {{ $r->target_porsi }}</td>
                            <td class="px-6 py-4">{{ $r->petugas->user->name ?? '-' }}</td>
                            <td class="px-6 py-4">
                                @if($r->porsi_diterima >= $r->target_porsi)
                                    <span class="bg-blue-100 text-blue-700 text-xs px-2 py-1 rounded-full font-bold"><i
                                            class="fas fa-check mr-1"></i> Sesuai Target</span>
                                @else
                                    <span class="bg-red-100 text-red-700 text-xs px-2 py-1 rounded-full font-bold badge-pulse"><i
                                            class="fas fa-exclamation-triangle mr-1"></i> Kurang</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection