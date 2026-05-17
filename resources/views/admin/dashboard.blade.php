@extends('layouts.admin')

@section('header_title', 'Dashboard Penyaluran')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">

        <div class="stat-card bg-white rounded-lg shadow p-6 flex border-l-4 border-blue-500 animate-fade-in-up delay-1">
            <div class="flex-1">
                <p class="text-sm text-gray-500 font-semibold uppercase">Total Penerima Aktif</p>
                <p class="text-3xl font-bold text-gray-800 mt-2 number-animate delay-3">{{ number_format($data['total_penerima'], 0, ',', '.') }}
                </p>
            </div>
            <div class="text-blue-500 text-3xl flex items-center stat-card-icon">
                <i class="fas fa-user-graduate"></i>
            </div>
        </div>

        <div class="stat-card bg-white rounded-lg shadow p-6 flex border-l-4 border-green-500 animate-fade-in-up delay-2">
            <div class="flex-1">
                <p class="text-sm text-gray-500 font-semibold uppercase">Tersalurkan Hari Ini</p>
                <p class="text-3xl font-bold text-gray-800 mt-2 number-animate delay-4">
                    {{ number_format($data['tersalurkan_hari_ini'], 0, ',', '.') }}</p>
                <p class="text-xs text-gray-400 mt-1">Dari target {{ number_format($data['target_hari_ini'], 0, ',', '.') }}
                    porsi</p>
            </div>
            <div class="text-green-500 text-3xl flex items-center stat-card-icon">
                <i class="fas fa-box-open"></i>
            </div>
        </div>

        <div class="stat-card bg-white rounded-lg shadow p-6 flex border-l-4 border-yellow-500 animate-fade-in-up delay-3">
            <div class="flex-1">
                <p class="text-sm text-gray-500 font-semibold uppercase">Progres Harian</p>
                <p class="text-3xl font-bold text-gray-800 mt-2 number-animate delay-5">{{ $data['persentase_harian'] }}%</p>
                <div class="w-full bg-gray-200 rounded-full h-2.5 mt-2 overflow-hidden">
                    <div class="progress-animate bg-yellow-500 h-2.5 rounded-full transition-all duration-1000 ease-out" 
                         style="width: 0%;" 
                         data-target="{{ $data['persentase_harian'] }}"></div>
                </div>
            </div>
        </div>

        <div class="stat-card bg-white rounded-lg shadow p-6 flex border-l-4 border-purple-500 animate-fade-in-up delay-4">
            <div class="flex-1">
                <p class="text-sm text-gray-500 font-semibold uppercase">Titik Distribusi</p>
                <p class="text-3xl font-bold text-gray-800 mt-2 number-animate delay-6">{{ $data['titik_distribusi'] }}</p>
                <p class="text-xs text-gray-400 mt-1">Sekolah / Yayasan</p>
            </div>
            <div class="text-purple-500 text-3xl flex items-center stat-card-icon">
                <i class="fas fa-map-marker-alt"></i>
            </div>
        </div>

    </div>

    <div class="bg-white rounded-lg shadow mb-8 animate-fade-in-up delay-5 card-hover">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="font-bold text-gray-800 text-lg">Aktivitas Penyaluran Terbaru (Hari Ini)</h3>
            <a href="{{ route('admin.laporan.index') }}"
                class="btn-animate bg-blue-50 text-blue-600 px-3 py-1 rounded text-sm font-semibold hover:bg-blue-100">Lihat
                Semua</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full whitespace-nowrap">
                <thead class="bg-gray-50 text-gray-600 text-left text-sm uppercase font-semibold">
                    <tr>
                        <th class="px-6 py-3">Waktu</th>
                        <th class="px-6 py-3">Titik Distribusi</th>
                        <th class="px-6 py-3">Jumlah Porsi</th>
                        <th class="px-6 py-3">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 text-gray-700">
                    @foreach($recent_activities as $index => $activity)
                        <tr class="table-row-animate animate-fade-in-up" style="animation-delay: {{ 0.5 + ($index * 0.08) }}s;">
                            <td class="px-6 py-4">{{ $activity->updated_at ? $activity->updated_at->format('H:i') : '-' }} WIB</td>
                            <td class="px-6 py-4 font-medium">{{ $activity->sekolah->nama_sekolah ?? '-' }}</td>
                            <td class="px-6 py-4">{{ $activity->target_porsi }} Porsi</td>
                            <td class="px-6 py-4">
                                @if($activity->status_pengiriman === 'Belum Dikirim')
                                    <span class="bg-gray-100 text-gray-800 text-xs font-bold px-2.5 py-1 rounded-full border border-gray-400 whitespace-nowrap">
                                        <i class="fas fa-clock mr-1"></i> Belum Dikirim
                                    </span>
                                @elseif($activity->status_pengiriman === 'Dalam Perjalanan')
                                    <div class="flex flex-col gap-1">
                                        <span class="badge-pulse bg-blue-100 text-blue-800 text-xs font-bold px-2.5 py-1 rounded-full border border-blue-400 whitespace-nowrap inline-flex items-center w-max">
                                            <span class="dot-blink" style="background: #3b82f6;"></span> Sedang Dikirim
                                        </span>
                                        <div class="text-[10px] text-gray-500 font-semibold whitespace-nowrap">
                                            Petugas <i class="fas fa-check-circle text-green-500 mx-0.5"></i> | Sekolah <i class="fas fa-hourglass-half text-yellow-500 ml-0.5"></i>
                                        </div>
                                    </div>
                                @elseif($activity->status_pengiriman === 'Selesai')
                                    <div class="flex flex-col gap-1">
                                        <span class="bg-green-100 text-green-800 text-xs font-bold px-2.5 py-1 rounded-full border border-green-400 whitespace-nowrap inline-flex items-center w-max">
                                            <i class="fas fa-check-double mr-1.5"></i> Selesai
                                        </span>
                                        <div class="text-[10px] text-gray-500 font-semibold whitespace-nowrap">
                                            Petugas <i class="fas fa-check-circle text-green-500 mx-0.5"></i> | Sekolah <i class="fas fa-check-circle text-green-500 ml-0.5"></i>
                                        </div>
                                    </div>
                                @else
                                    <span class="bg-red-100 text-red-800 text-xs font-bold px-2.5 py-1 rounded-full border border-red-400">{{ $activity->status_pengiriman }}</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script>
        // Animate progress bars on load
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                document.querySelectorAll('[data-target]').forEach(function(bar) {
                    bar.style.width = bar.dataset.target + '%';
                });
            }, 600);
        });
    </script>
@endsection