@extends('layouts.admin')

@section('header_title', 'Status Pengiriman Real-Time')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="p-6 border-b border-slate-200 flex justify-between items-center bg-slate-50/50">
        <div>
            <h3 class="text-lg font-bold text-slate-800">Monitoring Kurir Hari Ini</h3>
            <p class="text-sm text-slate-500">Pelacakan distribusi makanan bergizi ke sekolah-sekolah</p>
        </div>
        <a href="{{ route('admin.status.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition flex items-center">
            <i class="fas fa-sync-alt mr-2"></i> Refresh Data
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-slate-50 text-slate-600 text-sm uppercase font-semibold">
                <tr>
                    <th class="px-6 py-4">Tujuan Sekolah</th>
                    <th class="px-6 py-4">Petugas / Kurir</th>
                    <th class="px-6 py-4 text-center">Porsi</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Lokasi Terakhir</th>
                    <th class="px-6 py-4 text-center">Update</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-slate-700">
                @forelse($status_pengiriman as $index => $status)
                <tr class="hover:bg-slate-50/80 transition">
                    <td class="px-6 py-4">
                        <div class="font-bold text-slate-800">{{ $status->sekolah->nama_sekolah ?? '-' }}</div>
                        <div class="text-xs text-slate-400 font-medium tracking-wide mt-0.5">{{ $status->tanggal->format('d M Y') }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center text-slate-600 mr-3">
                                <i class="fas fa-user-circle"></i>
                            </div>
                            <span class="font-medium font-sans">{{ $status->petugas->user->name ?? '-' }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="bg-slate-100 text-slate-700 px-2 py-1 rounded-md text-xs font-bold border border-slate-200">
                            {{ $status->porsi_diterima ?? $status->target_porsi }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        @if($status->status_pengiriman == 'Selesai')
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800 border border-green-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-1.5"></span>
                                {{ $status->status_pengiriman }}
                            </span>
                        @elseif($status->status_pengiriman == 'Dalam Perjalanan')
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800 border border-blue-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-blue-500 mr-1.5 animate-pulse"></span>
                                {{ $status->status_pengiriman }}
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-400 mr-1.5"></span>
                                {{ $status->status_pengiriman }}
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-start max-w-xs">
                            <i class="fas fa-map-marker-alt text-red-500 mt-1 mr-2 text-sm"></i>
                            <span class="text-sm leading-relaxed">{{ $status->sekolah->alamat ?? '-' }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-center text-sm font-medium text-slate-500">
                        {{ $status->updated_at->format('H:i') }} WIB
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-10 text-center text-gray-400">
                        <i class="fas fa-truck text-3xl mb-2 block"></i>
                        Belum ada data distribusi.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="p-4 border-t border-slate-200 bg-slate-50/50">
        <div class="flex items-center text-xs text-slate-400 italic">
            <i class="fas fa-info-circle mr-2 not-italic text-blue-400"></i>
            Data diperbarui secara otomatis setiap 5 menit melalui sistem tracking kurir GPS terintegrasi.
        </div>
    </div>
</div>
@endsection
