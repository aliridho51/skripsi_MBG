@extends('layouts.admin')

@section('header_title', 'Grafik & Monitoring Penyaluran')

@section('content')
    <!-- Filter Periode & Tahun -->
    <div class="bg-white rounded-xl shadow-md p-6 mb-8 border border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4 animate-fade-in-up">
        <div>
            <h3 class="text-lg font-bold text-slate-800">Filter Analisis Penyaluran</h3>
            <p class="text-xs text-slate-500 mt-1">Sesuaikan periode waktu untuk memantau detail porsi dan efisiensi penyaluran.</p>
        </div>
        <form method="GET" action="{{ route('admin.monitoring.index') }}" class="flex flex-wrap items-center gap-3">
            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Periode</label>
                <select name="periode" onchange="this.form.submit()" class="bg-slate-50 border border-slate-200 text-slate-700 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 font-medium">
                    <option value="bulanan" {{ $periode === 'bulanan' ? 'selected' : '' }}>Bulanan (Tahunan)</option>
                    <option value="mingguan" {{ $periode === 'mingguan' ? 'selected' : '' }}>Mingguan (7 Hari Terakhir)</option>
                </select>
            </div>
            
            @if($periode === 'bulanan')
            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Tahun</label>
                <select name="tahun" onchange="this.form.submit()" class="bg-slate-50 border border-slate-200 text-slate-700 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 font-medium">
                    @foreach($tahunList as $th)
                        <option value="{{ $th }}" {{ $tahun == $th ? 'selected' : '' }}>{{ $th }}</option>
                    @endforeach
                    @if(!in_array(now()->year, $tahunList->toArray() ?? []))
                        <option value="{{ now()->year }}" {{ $tahun == now()->year ? 'selected' : '' }}>{{ now()->year }}</option>
                    @endif
                </select>
            </div>
            @endif

            <button type="submit" class="mt-5 btn-ripple bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2.5 rounded-lg flex items-center shadow-sm transition-all">
                <i class="fas fa-sync mr-2"></i> Update Data
            </button>
        </form>
    </div>

    <!-- Ringkasan Statistik -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Target Porsi -->
        <div class="stat-card bg-white rounded-xl shadow-sm border border-slate-100 p-6 flex items-center justify-between hover:shadow-md transition duration-200">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Target Porsi</p>
                <h3 class="text-3xl font-extrabold text-slate-800 mt-2">{{ number_format($totalTarget, 0, ',', '.') }}</h3>
                <span class="text-xs text-slate-500 mt-1 inline-block">Jumlah porsi yang direncanakan</span>
            </div>
            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center text-xl">
                <i class="fas fa-bullseye"></i>
            </div>
        </div>

        <!-- Tersalurkan -->
        <div class="stat-card bg-white rounded-xl shadow-sm border border-slate-100 p-6 flex items-center justify-between hover:shadow-md transition duration-200">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Porsi Tersalurkan</p>
                <h3 class="text-3xl font-extrabold text-emerald-600 mt-2">{{ number_format($totalTersalurkan, 0, ',', '.') }}</h3>
                <span class="text-xs text-emerald-500 font-semibold mt-1 inline-flex items-center">
                    <i class="fas fa-check-circle mr-1"></i> {{ $persentaseTotal }}% Terpenuhi
                </span>
            </div>
            <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-lg flex items-center justify-center text-xl">
                <i class="fas fa-hand-holding-heart"></i>
            </div>
        </div>

        <!-- Kekurangan Porsi -->
        <div class="stat-card bg-white rounded-xl shadow-sm border border-slate-100 p-6 flex items-center justify-between hover:shadow-md transition duration-200">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Kekurangan</p>
                <h3 class="text-3xl font-extrabold text-rose-600 mt-2">{{ number_format($totalKekurangan, 0, ',', '.') }}</h3>
                <span class="text-xs text-rose-500 font-semibold mt-1 inline-flex items-center">
                    <i class="fas fa-exclamation-triangle mr-1"></i> Selisih target & diterima
                </span>
            </div>
            <div class="w-12 h-12 bg-rose-50 text-rose-600 rounded-lg flex items-center justify-center text-xl">
                <i class="fas fa-chart-line-down"></i>
            </div>
        </div>

        <!-- Efektivitas Status -->
        <div class="stat-card bg-white rounded-xl shadow-sm border border-slate-100 p-6 flex items-center justify-between hover:shadow-md transition duration-200">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Selesai / Total</p>
                <h3 class="text-3xl font-extrabold text-indigo-600 mt-2">
                    {{ $totalSelesai }}/{{ $totalSelesai + $totalDalamPerjalanan + $totalBelumDikirim + $totalAdaKendala }}
                </h3>
                <span class="text-xs text-indigo-500 font-semibold mt-1 inline-flex items-center">
                    <i class="fas fa-truck mr-1"></i> Berhasil diserahterimakan
                </span>
            </div>
            <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-lg flex items-center justify-center text-xl">
                <i class="fas fa-shipping-fast"></i>
            </div>
        </div>
    </div>

    <!-- Grafik Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <!-- Grafik Utama -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 lg:col-span-2">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h4 class="text-base font-bold text-slate-800">Tren Penyaluran Makanan Bergizi</h4>
                    <p class="text-xs text-slate-400">Membandingkan target porsi dengan realisasi penyaluran</p>
                </div>
                <div class="flex gap-2">
                    <span class="inline-flex items-center text-xs font-semibold text-slate-500 bg-slate-100 px-2.5 py-1 rounded">
                        <span class="w-2 h-2 rounded-full bg-blue-500 mr-1.5"></span> Target
                    </span>
                    <span class="inline-flex items-center text-xs font-semibold text-slate-500 bg-slate-100 px-2.5 py-1 rounded">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 mr-1.5"></span> Tersalurkan
                    </span>
                </div>
            </div>
            <div class="relative h-[320px] w-full">
                <canvas id="trenPenyaluranChart"></canvas>
            </div>
        </div>

        <!-- Donut Status Pengiriman -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 flex flex-col justify-between">
            <div>
                <h4 class="text-base font-bold text-slate-800 mb-1">Status Pengantaran</h4>
                <p class="text-xs text-slate-400 mb-6">Distribusi paket makanan secara real-time</p>
            </div>
            <div class="relative h-[220px] w-full mb-6 flex justify-center items-center">
                <canvas id="statusPengirimanChart"></canvas>
            </div>
            <div class="grid grid-cols-2 gap-2 text-xs">
                <div class="flex items-center gap-1.5 p-2 bg-slate-50 rounded">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                    <span class="text-slate-600 font-medium">Selesai ({{ $totalSelesai }})</span>
                </div>
                <div class="flex items-center gap-1.5 p-2 bg-slate-50 rounded">
                    <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>
                    <span class="text-slate-600 font-medium">Kirim ({{ $totalDalamPerjalanan }})</span>
                </div>
                <div class="flex items-center gap-1.5 p-2 bg-slate-50 rounded">
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                    <span class="text-slate-600 font-medium">Pending ({{ $totalBelumDikirim }})</span>
                </div>
                <div class="flex items-center gap-1.5 p-2 bg-slate-50 rounded">
                    <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
                    <span class="text-slate-600 font-medium">Kendala ({{ $totalAdaKendala }})</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Analisis Sekolah & Kepuasan Pelanggan -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
        <!-- Monitoring per Sekolah -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 xl:col-span-2">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h4 class="text-base font-bold text-slate-800">Detail Distribusi & Selisih per Sekolah</h4>
                    <p class="text-xs text-slate-400">Data akumulatif target porsi vs realisasi yang diterima</p>
                </div>
                <span class="text-xs bg-slate-50 text-slate-500 px-3 py-1 rounded-lg border border-slate-100">
                    Total: {{ count($perSekolah) }} Sekolah
                </span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm whitespace-nowrap">
                    <thead>
                        <tr class="border-b border-slate-100 text-slate-400 text-xs font-bold uppercase">
                            <th class="pb-3 font-semibold">Nama Sekolah</th>
                            <th class="pb-3 text-center font-semibold">Target</th>
                            <th class="pb-3 text-center font-semibold">Diterima</th>
                            <th class="pb-3 text-center font-semibold">Selisih</th>
                            <th class="pb-3 text-center font-semibold">Tingkat Pemenuhan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse($perSekolah as $sekolah)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="py-3.5 pr-3 font-semibold text-slate-800">{{ $sekolah->nama_sekolah }}</td>
                                <td class="py-3.5 text-center font-medium text-slate-500">{{ number_format($sekolah->total_target ?? 0, 0, ',', '.') }}</td>
                                <td class="py-3.5 text-center font-bold text-emerald-600">{{ number_format($sekolah->total_diterima ?? 0, 0, ',', '.') }}</td>
                                <td class="py-3.5 text-center">
                                    @if($sekolah->total_kekurangan > 0)
                                        <span class="bg-rose-50 text-rose-600 px-2 py-0.5 rounded text-xs font-bold inline-flex items-center">
                                            -{{ number_format($sekolah->total_kekurangan, 0, ',', '.') }} porsi
                                        </span>
                                    @else
                                        <span class="bg-emerald-50 text-emerald-600 px-2 py-0.5 rounded text-xs font-bold inline-flex items-center">
                                            <i class="fas fa-check mr-1 text-[10px]"></i> Sesuai
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3.5 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <div class="w-16 bg-slate-100 rounded-full h-1.5 overflow-hidden">
                                            <div class="h-1.5 rounded-full {{ $sekolah->persen >= 100 ? 'bg-emerald-500' : ($sekolah->persen >= 75 ? 'bg-blue-500' : 'bg-rose-500') }}" style="width: {{ min(100, $sekolah->persen) }}%"></div>
                                        </div>
                                        <span class="text-xs font-bold text-slate-600">{{ $sekolah->persen }}%</span>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-6 text-center text-slate-400">Tidak ada data sekolah atau jadwal distribusi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Umpan Balik / Kritik Saran Terbaru -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 flex flex-col justify-between">
            <div>
                <h4 class="text-base font-bold text-slate-800 mb-1">Aktivitas Umpan Balik</h4>
                <p class="text-xs text-slate-400 mb-6">Tanggapan & keluhan terkait kekurangan porsi</p>

                <div class="space-y-4">
                    @forelse($umpanBalik as $saran)
                        <div class="p-3.5 bg-slate-50 rounded-lg border border-slate-100 flex gap-3 hover:shadow-sm transition">
                            <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex-shrink-0 flex items-center justify-center text-xs font-bold">
                                {{ substr($saran->sekolah->nama_sekolah ?? 'S', 0, 1) }}
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="flex justify-between items-start gap-1">
                                    <h5 class="text-xs font-bold text-slate-800 truncate">{{ $saran->sekolah->nama_sekolah ?? 'Sekolah' }}</h5>
                                    <span class="text-[10px] text-slate-400 whitespace-nowrap">{{ $saran->created_at ? $saran->created_at->diffForHumans() : '-' }}</span>
                                </div>
                                <p class="text-xs text-slate-600 mt-1 italic line-clamp-2">"{{ $saran->komentar }}"</p>
                            </div>
                        </div>
                    @empty
                        <div class="py-8 text-center text-slate-400 flex flex-col items-center justify-center">
                            <i class="far fa-comment-dots text-3xl mb-2 text-slate-300"></i>
                            <span class="text-xs">Belum ada umpan balik yang dikirimkan.</span>
                        </div>
                    @endforelse
                </div>
            </div>
            
            <a href="{{ route('admin.kritik-saran.index') }}" class="mt-6 text-center text-xs font-bold text-blue-600 bg-blue-50 hover:bg-blue-100/80 p-2.5 rounded-lg transition">
                Lihat Semua Umpan Balik
            </a>
        </div>
    </div>

    <!-- Script Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // ─── DATA DRILLDOWN CHART TREN ────────────────────────────────
            const ctxTren = document.getElementById('trenPenyaluranChart').getContext('2d');
            
            // Gradient fill
            const targetGradient = ctxTren.createLinearGradient(0, 0, 0, 300);
            targetGradient.addColorStop(0, 'rgba(59, 130, 246, 0.4)');
            targetGradient.addColorStop(1, 'rgba(59, 130, 246, 0.0)');

            const tersalurGradient = ctxTren.createLinearGradient(0, 0, 0, 300);
            tersalurGradient.addColorStop(0, 'rgba(16, 185, 129, 0.4)');
            tersalurGradient.addColorStop(1, 'rgba(16, 185, 129, 0.0)');

            const trenChart = new Chart(ctxTren, {
                type: 'line',
                data: {
                    labels: {!! json_encode($chartLabels) !!},
                    datasets: [
                        {
                            label: 'Target Porsi',
                            data: {!! json_encode($chartTarget) !!},
                            borderColor: '#3b82f6',
                            borderWidth: 3,
                            backgroundColor: targetGradient,
                            fill: true,
                            tension: 0.35,
                            pointBackgroundColor: '#3b82f6',
                            pointHoverRadius: 7
                        },
                        {
                            label: 'Porsi Tersalurkan',
                            data: {!! json_encode($chartTersalur) !!},
                            borderColor: '#10b981',
                            borderWidth: 3,
                            backgroundColor: tersalurGradient,
                            fill: true,
                            tension: 0.35,
                            pointBackgroundColor: '#10b981',
                            pointHoverRadius: 7
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            padding: 12,
                            backgroundColor: 'rgba(15, 23, 42, 0.9)',
                            titleFont: { size: 13, weight: 'bold' },
                            bodyFont: { size: 12 },
                            cornerRadius: 8,
                            boxPadding: 6
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    family: 'sans-serif',
                                    size: 11
                                },
                                color: '#64748b'
                            }
                        },
                        y: {
                            grid: {
                                borderDash: [5, 5],
                                color: '#e2e8f0'
                            },
                            ticks: {
                                font: {
                                    family: 'sans-serif',
                                    size: 11
                                },
                                color: '#64748b'
                            }
                        }
                    }
                }
            });

            // ─── DATA DONUT CHART STATUS ──────────────────────────────────
            const ctxStatus = document.getElementById('statusPengirimanChart').getContext('2d');
            const statusChart = new Chart(ctxStatus, {
                type: 'doughnut',
                data: {
                    labels: ['Selesai', 'Dalam Perjalanan', 'Belum Dikirim', 'Ada Kendala'],
                    datasets: [{
                        data: [
                            {{ $statusData['Selesai'] }},
                            {{ $statusData['Dalam Perjalanan'] }},
                            {{ $statusData['Belum Dikirim'] }},
                            {{ $statusData['Ada Kendala'] }}
                        ],
                        backgroundColor: ['#10b981', '#3b82f6', '#f59e0b', '#f43f5e'],
                        borderWidth: 4,
                        borderColor: '#ffffff',
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%',
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            padding: 10,
                            backgroundColor: 'rgba(15, 23, 42, 0.9)',
                            titleFont: { size: 12, weight: 'bold' },
                            bodyFont: { size: 12 },
                            cornerRadius: 6
                        }
                    }
                }
            });
        });
    </script>
@endsection
