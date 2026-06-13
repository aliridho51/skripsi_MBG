@extends('layouts.sekolah')
@section('header_title', 'Dashboard & Info Kuota')
@section('content')
    <div class="max-w-3xl">
        @if($distribusi_hari_ini)
            <div class="bg-white rounded-2xl shadow-sm border-l-8 border-blue-500 p-6 mb-6 animate-fade-in-up card-hover">
                <h3 class="text-xl font-bold text-slate-800 mb-2">Informasi Pengiriman Hari Ini</h3>
                <p class="text-slate-500 mb-4">{{ $info_hari_ini['tanggal'] }}</p>

                {{-- Foto Menu Hari Ini --}}
                @if($distribusi_hari_ini && ($distribusi_hari_ini->foto_menu_data || $distribusi_hari_ini->foto_menu))
                <div class="mb-5 rounded-xl overflow-hidden border border-slate-200 shadow-sm animate-fade-in-up delay-2">
                    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-4 py-2.5 flex items-center">
                        <i class="fas fa-utensils text-white mr-2"></i>
                        <span class="text-white text-sm font-bold">Menu Hari Ini</span>
                    </div>
                    <div class="relative overflow-hidden group">
                        @php
                            // Prioritas: gunakan base64 dari DB agar foto tidak hilang saat redeploy Railway
                            if ($distribusi_hari_ini->foto_menu_data) {
                                $fotoMenuSrc = $distribusi_hari_ini->foto_menu_data;
                            } elseif ($distribusi_hari_ini->foto_menu && file_exists(public_path($distribusi_hari_ini->foto_menu))) {
                                $fotoMenuSrc = asset($distribusi_hari_ini->foto_menu);
                            } else {
                                $fotoMenuSrc = asset('images/default_menu.png');
                            }
                        @endphp
                        <img src="{{ $fotoMenuSrc }}"
                             alt="Foto Menu Hari Ini"
                             class="w-full max-h-64 object-cover cursor-pointer transition-transform duration-500 group-hover:scale-105"
                             onclick="openImageModal(this.src)"
                             loading="lazy">
                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-all duration-300 flex items-center justify-center">
                            <i class="fas fa-search-plus text-white text-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></i>
                        </div>
                    </div>
                    @if($distribusi_hari_ini->menu_hari_ini)
                    <div class="px-4 py-3 bg-slate-50 border-t border-slate-100">
                        <p class="text-sm text-slate-700 font-medium">
                            <i class="fas fa-list-ul text-blue-500 mr-1.5"></i>
                            {{ $distribusi_hari_ini->menu_hari_ini }}
                        </p>
                    </div>
                    @endif
                </div>
                @elseif($distribusi_hari_ini && $distribusi_hari_ini->menu_hari_ini)
                <div class="mb-5 p-4 bg-blue-50 rounded-xl border border-blue-100 animate-fade-in-up delay-2">
                    <p class="text-xs font-bold text-blue-400 uppercase tracking-wider mb-1">Menu Hari Ini</p>
                    <p class="text-sm font-semibold text-blue-800">
                        <i class="fas fa-utensils mr-1.5"></i>
                        {{ $distribusi_hari_ini->menu_hari_ini }}
                    </p>
                </div>
                @endif

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-4">
                    <div class="stat-card bg-slate-50 p-4 rounded-xl text-center border border-slate-100 animate-fade-in-up delay-3">
                        <p class="text-xs font-bold text-slate-400 uppercase">Total Kuota</p>
                        <p class="text-2xl font-black text-blue-600 mt-1 number-animate delay-5">{{ $info_hari_ini['kuota'] }}</p>
                    </div>
                    <div class="stat-card bg-slate-50 p-4 rounded-xl text-center border border-slate-100 col-span-2 animate-fade-in-up delay-4">
                        <p class="text-xs font-bold text-slate-400 uppercase">Status Saat Ini</p>
                        <p class="text-lg font-bold text-blue-600 mt-2">
                            @if($info_hari_ini['status'] === 'Dalam Perjalanan')
                                <span class="inline-flex items-center badge-pulse">
                                    <span class="dot-blink" style="background: #3b82f6;"></span>
                                    <i class="fas fa-truck mr-2"></i>{{ $info_hari_ini['status'] }}
                                </span>
                            @else
                                <i class="fas fa-truck mr-2"></i>{{ $info_hari_ini['status'] }}
                            @endif
                        </p>
                    </div>
                    <div class="stat-card bg-slate-50 p-4 rounded-xl text-center border border-slate-100 animate-fade-in-up delay-5">
                        <p class="text-xs font-bold text-slate-400 uppercase">Estimasi Tiba</p>
                        <p class="text-xl font-bold text-slate-700 mt-1 number-animate delay-7">{{ $info_hari_ini['estimasi_tiba'] }}</p>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-slate-100 flex items-center text-sm text-slate-500 animate-fade-in delay-6">
                    <i class="fas fa-user-shield text-blue-500 mr-2"></i>
                    Petugas Pengirim: <strong class="ml-1 text-slate-700">{{ $info_hari_ini['petugas'] }}</strong>
                </div>
            </div>
        @else
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-10 text-center animate-fade-in-scale mt-4">
                <div class="w-24 h-24 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-blue-100">
                    <i class="fas fa-calendar-times text-4xl text-blue-400"></i>
                </div>
                <h3 class="text-2xl font-bold text-slate-800 mb-2">Belum Ada Jadwal Pengiriman</h3>
                <p class="text-slate-500 max-w-md mx-auto mb-6">Saat ini belum ada jadwal pengiriman Makanan Bergizi Gratis (MBG) yang aktif untuk sekolah Anda. Silakan cek secara berkala.</p>
                <button onclick="window.location.reload()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg font-medium transition-colors shadow-sm inline-flex items-center">
                    <i class="fas fa-sync-alt mr-2"></i> Muat Ulang
                </button>
            </div>
        @endif
    </div>

    {{-- Image Modal --}}
    <div id="imageModal" class="fixed inset-0 bg-black/80 z-[100] hidden items-center justify-center p-4 modal-overlay-animate" onclick="closeImageModal()">
        <div class="relative max-w-3xl w-full modal-content-animate">
            <button onclick="closeImageModal()" class="absolute -top-10 right-0 text-white text-2xl hover:text-red-400 transition">
                <i class="fas fa-times"></i>
            </button>
            <img id="modalImage" src="" alt="Preview" class="w-full rounded-xl shadow-2xl">
        </div>
    </div>

    <script>
    function openImageModal(src) {
        const modal = document.getElementById('imageModal');
        const img = document.getElementById('modalImage');
        img.src = src;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
    function closeImageModal() {
        const modal = document.getElementById('imageModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    // Auto-refresh dashboard setiap 30 detik ketika pengiriman sedang aktif
    @if($distribusi_hari_ini && in_array($distribusi_hari_ini->status_pengiriman, ['Belum Dikirim', 'Dalam Perjalanan']))
    let dashboardCountdown = 30;
    const dashBadge = document.createElement('div');
    dashBadge.innerHTML = `
        <div id="dashRefreshBar" class="fixed bottom-4 right-4 z-50 bg-slate-800/90 text-white text-xs font-bold px-4 py-2.5 rounded-full shadow-lg flex items-center gap-2 backdrop-blur-sm">
            <span class="w-2 h-2 bg-blue-400 rounded-full animate-pulse inline-block"></span>
            <span>Live &bull; Update dalam <span id="dashCountdown">30</span>s</span>
            <button onclick="location.reload()" class="ml-1 bg-blue-500 hover:bg-blue-400 text-white text-[10px] px-2 py-0.5 rounded-full transition">Refresh</button>
        </div>`;
    document.body.appendChild(dashBadge);

    const dashInterval = setInterval(() => {
        dashboardCountdown--;
        const el = document.getElementById('dashCountdown');
        if (el) el.textContent = dashboardCountdown;
        if (dashboardCountdown <= 0) {
            clearInterval(dashInterval);
            location.reload();
        }
    }, 1000);
    @endif
    </script>
@endsection