@extends('layouts.admin')

@section('header_title', 'Laporan Penyaluran')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow border-l-4 border-blue-500">
            <label class="text-sm font-bold text-gray-500 uppercase block mb-2">Filter Rentang Waktu</label>
            <div class="flex space-x-2">
                <input type="date"
                    class="border rounded px-3 py-2 text-sm w-full focus:ring-blue-500 focus:border-blue-500">
                <button class="bg-slate-800 text-white px-4 py-2 rounded text-sm hover:bg-slate-700">Filter</button>
            </div>
        </div>
        <div class="bg-white p-6 rounded-lg shadow border-l-4 border-green-500 flex items-center justify-between">
            <div>
                <h4 class="font-bold text-gray-800">Ekspor Data</h4>
                <p class="text-xs text-gray-500">Unduh riwayat penyaluran format PDF/Excel</p>
            </div>
            <div class="flex space-x-2">
                <button class="bg-green-600 text-white px-4 py-2 rounded text-sm hover:bg-green-700"><i
                        class="fas fa-file-excel mr-2"></i>Excel</button>
                <button class="bg-red-600 text-white px-4 py-2 rounded text-sm hover:bg-red-700"><i
                        class="fas fa-file-pdf mr-2"></i>PDF</button>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="font-bold text-gray-800 text-lg">Riwayat Penyaluran Terakhir</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full whitespace-nowrap text-left">
                <thead class="bg-gray-50 text-gray-600 text-sm uppercase font-semibold">
                    <tr>
                        <th class="px-6 py-3">Tanggal</th>
                        <th class="px-6 py-3">Realisasi (Porsi)</th>
                        <th class="px-6 py-3">Target (Porsi)</th>
                        <th class="px-6 py-3">Persentase</th>
                        <th class="px-6 py-3">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 text-gray-700">
                    @forelse($laporan as $lap)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 font-medium">
                                {{ \Carbon\Carbon::parse($lap['tanggal'])->translatedFormat('d F Y') }}</td>
                            <td class="px-6 py-4">{{ $lap['total_disalurkan'] }}</td>
                            <td class="px-6 py-4">{{ $lap['target'] }}</td>
                            <td class="px-6 py-4">
                                @php $persen = $lap['target'] > 0 ? ($lap['total_disalurkan'] / $lap['target']) * 100 : 0; @endphp
                                <div class="flex items-center">
                                    <span class="mr-2">{{ round($persen) }}%</span>
                                    <div class="w-24 bg-gray-200 rounded-full h-1.5">
                                        <div class="bg-blue-600 h-1.5 rounded-full" style="width: {{ min($persen,100) }}%"></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="px-3 py-1 inline-flex text-xs font-semibold rounded-full {{ $lap['status'] == 'Selesai' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $lap['status'] }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-gray-400">
                                <i class="fas fa-chart-bar text-3xl mb-2 block"></i>
                                Belum ada data laporan distribusi.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection