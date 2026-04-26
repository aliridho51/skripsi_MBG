@extends('layouts.admin')

@section('header_title', 'Dashboard Penyaluran')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">

        <div class="bg-white rounded-lg shadow p-6 flex border-l-4 border-blue-500">
            <div class="flex-1">
                <p class="text-sm text-gray-500 font-semibold uppercase">Total Penerima Aktif</p>
                <p class="text-3xl font-bold text-gray-800 mt-2">{{ number_format($data['total_penerima'], 0, ',', '.') }}
                </p>
            </div>
            <div class="text-blue-500 text-3xl flex items-center">
                <i class="fas fa-user-graduate"></i>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 flex border-l-4 border-green-500">
            <div class="flex-1">
                <p class="text-sm text-gray-500 font-semibold uppercase">Tersalurkan Hari Ini</p>
                <p class="text-3xl font-bold text-gray-800 mt-2">
                    {{ number_format($data['tersalurkan_hari_ini'], 0, ',', '.') }}</p>
                <p class="text-xs text-gray-400 mt-1">Dari target {{ number_format($data['target_hari_ini'], 0, ',', '.') }}
                    porsi</p>
            </div>
            <div class="text-green-500 text-3xl flex items-center">
                <i class="fas fa-box-open"></i>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 flex border-l-4 border-yellow-500">
            <div class="flex-1">
                <p class="text-sm text-gray-500 font-semibold uppercase">Progres Harian</p>
                <p class="text-3xl font-bold text-gray-800 mt-2">{{ $data['persentase_harian'] }}%</p>
                <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                    <div class="bg-yellow-500 h-2 rounded-full" style="width: {{ $data['persentase_harian'] }}%"></div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 flex border-l-4 border-purple-500">
            <div class="flex-1">
                <p class="text-sm text-gray-500 font-semibold uppercase">Titik Distribusi</p>
                <p class="text-3xl font-bold text-gray-800 mt-2">{{ $data['titik_distribusi'] }}</p>
                <p class="text-xs text-gray-400 mt-1">Sekolah / Yayasan</p>
            </div>
            <div class="text-purple-500 text-3xl flex items-center">
                <i class="fas fa-map-marker-alt"></i>
            </div>
        </div>

    </div>

    <div class="bg-white rounded-lg shadow mb-8">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="font-bold text-gray-800 text-lg">Aktivitas Penyaluran Terbaru (Hari Ini)</h3>
            <a href="{{ route('admin.laporan.index') }}"
                class="bg-blue-50 text-blue-600 px-3 py-1 rounded text-sm font-semibold hover:bg-blue-100 transition">Lihat
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
                    @foreach($recent_activities as $activity)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">{{ $activity['waktu'] }}</td>
                            <td class="px-6 py-4 font-medium">{{ $activity['titik'] }}</td>
                            <td class="px-6 py-4">{{ $activity['porsi'] }} Porsi</td>
                            <td class="px-6 py-4">
                                @if($activity['status'] == 'Selesai')
                                    <span
                                        class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        {{ $activity['status'] }}
                                    </span>
                                @else
                                    <span
                                        class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        {{ $activity['status'] }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection