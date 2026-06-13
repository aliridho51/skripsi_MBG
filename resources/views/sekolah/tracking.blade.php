@extends('layouts.sekolah')
@section('header_title', 'Lacak Pengiriman')
@section('content')

{{-- Leaflet CSS & JS for Live Map Tracking --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

{{-- GoFood-style Delivery Tracking --}}
<div class="max-w-2xl mx-auto">

    {{-- Main Tracking Card --}}
    <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden animate-fade-in-scale">

        {{-- Header with animated gradient --}}
        <div class="relative overflow-hidden bg-gradient-to-r from-blue-600 via-blue-700 to-indigo-700 px-6 py-6">
            <div class="absolute inset-0 opacity-10">
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-white rounded-full animate-pulse"></div>
                <div class="absolute -bottom-10 -left-10 w-32 h-32 bg-white rounded-full animate-pulse" style="animation-delay: 1s;"></div>
            </div>
            <!-- Animated dots background -->
            <div class="absolute inset-0 opacity-5">
                <div class="absolute top-4 left-1/4 w-2 h-2 bg-white rounded-full" style="animation: particleFloat1 8s ease-in-out infinite;"></div>
                <div class="absolute bottom-4 right-1/3 w-1.5 h-1.5 bg-white rounded-full" style="animation: particleFloat2 6s ease-in-out infinite;"></div>
            </div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-3">
                    <div class="animate-fade-in-left">
                        <p class="text-blue-200 text-xs font-bold uppercase tracking-widest">Status Pengiriman</p>
                        <h2 class="text-white text-xl font-black mt-1">
                            @if($distribusi && $distribusi->status_pengiriman === 'Dalam Perjalanan')
                                <i class="fas fa-truck-pickup mr-2 animate-bounce"></i> Sedang Dalam Perjalanan
                            @elseif($distribusi && $distribusi->status_pengiriman === 'Selesai')
                                <i class="fas fa-check-circle mr-2"></i> Makanan Telah Tiba
                            @elseif($distribusi && $distribusi->status_pengiriman === 'Belum Dikirim')
                                <i class="fas fa-clock mr-2"></i> Menunggu Pengiriman
                            @else
                                <i class="fas fa-calendar-times mr-2"></i> Tidak Ada Pengiriman
                            @endif
                        </h2>
                    </div>
                    <div class="text-right animate-fade-in-right">
                        <p class="text-blue-200 text-[10px] font-bold uppercase tracking-widest">ID</p>
                        <p class="text-white font-mono font-bold text-sm">{{ $id_pengiriman }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Live Tracking Map Section (GoFood Style) --}}
        @if($distribusi && in_array($distribusi->status_pengiriman, ['Dalam Perjalanan', 'Selesai', 'Belum Dikirim']))
        <div class="relative w-full h-64 bg-slate-100 border-b border-slate-200 overflow-hidden" id="tracking-map-container" style="z-index: 1;">
            <div id="map" class="w-full h-full relative z-0"></div>
            
            {{-- Map Overlay Info --}}
            <div class="absolute top-4 left-4 right-4 z-10 flex justify-between pointer-events-none animate-fade-in-down delay-2">
                <div class="bg-white/90 backdrop-blur-sm px-3 py-2 rounded-lg shadow-sm border border-slate-200 pointer-events-auto">
                    <p class="text-[10px] text-slate-500 font-bold uppercase tracking-wider mb-0.5">Petugas Kurir</p>
                    <p class="text-sm font-black text-slate-800 flex items-center">
                        <i class="fas fa-truck-pickup text-blue-600 mr-2"></i> {{ $distribusi->petugas->user->name ?? 'Petugas MBG' }}
                    </p>
                </div>
            </div>
        </div>
        @endif

        {{-- Estimated Time Section --}}
        @if($distribusi)
        <div class="px-6 py-5 bg-gradient-to-b from-slate-50 to-white border-b border-slate-100 animate-fade-in-up delay-1">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">Estimasi Waktu Tiba</p>
                    <div class="flex items-baseline mt-1">
                        <span class="text-3xl font-black text-slate-800 number-animate delay-3">{{ $estimasi_tiba }}</span>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">Waktu Aktual Tiba</p>
                    <p class="text-2xl font-black mt-1 number-animate delay-4 {{ $distribusi->waktu_tiba ? 'text-emerald-600' : 'text-slate-300' }}">
                        {{ $distribusi->waktu_tiba ? date('H:i', strtotime($distribusi->waktu_tiba)) . ' WIB' : '--:--' }}
                    </p>
                </div>
            </div>

            {{-- Progress Bar --}}
            @php
                $progress = 0;
                if($distribusi->status_pengiriman === 'Belum Dikirim') $progress = 10;
                elseif($distribusi->status_pengiriman === 'Dalam Perjalanan') $progress = 60;
                elseif($distribusi->status_pengiriman === 'Selesai') $progress = 100;
            @endphp
            <div class="mt-4 bg-slate-200 rounded-full h-2.5 overflow-hidden">
                <div class="h-full rounded-full transition-all duration-1000 ease-out progress-animate
                    {{ $progress === 100 ? 'bg-emerald-500' : 'bg-blue-500' }}"
                    style="width: 0%;"
                    id="progressBar"
                    data-target="{{ $progress }}">
                </div>
            </div>
            <div class="flex justify-between mt-2 text-[10px] font-bold uppercase tracking-widest text-slate-400">
                <span>Disiapkan</span>
                <span>Dikirim</span>
                <span>Tiba</span>
            </div>
        </div>
        @endif

        {{-- Timeline Steps (GoFood-style) --}}
        <div class="px-6 py-6">
            <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-5 animate-fade-in delay-2">Riwayat Aktivitas</h4>

            <div class="space-y-0">
                {{-- Step 1: Makanan Dijadwalkan --}}
                <div class="flex items-start group animate-fade-in-left delay-2">
                    <div class="flex flex-col items-center mr-4">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center shadow-md transition-all duration-500
                            {{ $distribusi ? 'bg-emerald-500 text-white ring-4 ring-emerald-100' : 'bg-slate-200 text-slate-400' }}">
                            <i class="fas fa-calendar-check text-sm"></i>
                        </div>
                        <div class="w-0.5 timeline-line-animate {{ $distribusi ? 'bg-emerald-300' : 'bg-slate-200' }}" style="animation-delay: 0.3s;"></div>
                    </div>
                    <div class="pt-1.5 flex-1">
                        <p class="font-bold text-sm {{ $distribusi ? 'text-slate-800' : 'text-slate-400' }}">Makanan Dijadwalkan</p>
                        <p class="text-xs text-slate-400 mt-0.5">
                            {{ $distribusi ? $distribusi->tanggal->translatedFormat('l, d F Y') : 'Belum ada jadwal' }}
                        </p>
                    </div>
                    @if($distribusi)
                    <span class="text-[10px] bg-emerald-100 text-emerald-700 font-bold px-2 py-0.5 rounded-full mt-2">Selesai</span>
                    @endif
                </div>

                {{-- Step 2: Makanan Sedang Disiapkan --}}
                <div class="flex items-start group animate-fade-in-left delay-3">
                    <div class="flex flex-col items-center mr-4">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center shadow-md transition-all duration-500
                            {{ $distribusi ? 'bg-emerald-500 text-white ring-4 ring-emerald-100' : 'bg-slate-200 text-slate-400' }}">
                            <i class="fas fa-utensils text-sm"></i>
                        </div>
                        <div class="w-0.5 timeline-line-animate {{ ($distribusi && in_array($distribusi->status_pengiriman, ['Dalam Perjalanan', 'Selesai'])) ? 'bg-emerald-300' : 'bg-slate-200' }}" style="animation-delay: 0.5s;"></div>
                    </div>
                    <div class="pt-1.5 flex-1">
                        <p class="font-bold text-sm {{ $distribusi ? 'text-slate-800' : 'text-slate-400' }}">Makanan Disiapkan</p>
                        <p class="text-xs text-slate-400 mt-0.5">Menu: {{ $distribusi ? ($distribusi->menu_hari_ini ?? 'Belum ada info menu') : '-' }}</p>
                    </div>
                    @if($distribusi)
                    <span class="text-[10px] bg-emerald-100 text-emerald-700 font-bold px-2 py-0.5 rounded-full mt-2">Selesai</span>
                    @endif
                </div>

                {{-- Step 3: Dalam Perjalanan --}}
                <div class="flex items-start group animate-fade-in-left delay-4">
                    <div class="flex flex-col items-center mr-4">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center shadow-md transition-all duration-500
                            @if($distribusi && in_array($distribusi->status_pengiriman, ['Dalam Perjalanan', 'Selesai']))
                                {{ $distribusi->status_pengiriman === 'Dalam Perjalanan' ? 'bg-blue-500 text-white ring-4 ring-blue-100 timeline-dot-active' : 'bg-emerald-500 text-white ring-4 ring-emerald-100' }}
                            @else
                                bg-slate-200 text-slate-400
                            @endif">
                            <i class="fas fa-truck text-sm"></i>
                        </div>
                        <div class="w-0.5 timeline-line-animate {{ ($distribusi && $distribusi->status_pengiriman === 'Selesai') ? 'bg-emerald-300' : 'bg-slate-200' }}" style="animation-delay: 0.7s;"></div>
                    </div>
                    <div class="pt-1.5 flex-1">
                        <p class="font-bold text-sm {{ ($distribusi && in_array($distribusi->status_pengiriman, ['Dalam Perjalanan', 'Selesai'])) ? 'text-slate-800' : 'text-slate-400' }}">Dalam Perjalanan</p>
                        <p class="text-xs text-slate-400 mt-0.5">
                            @if($distribusi && $distribusi->status_pengiriman === 'Dalam Perjalanan')
                                Petugas sedang mengantar ke sekolah Anda
                            @elseif($distribusi && $distribusi->status_pengiriman === 'Selesai')
                                Pengiriman berhasil dilakukan
                            @else
                                Menunggu petugas memulai pengiriman
                            @endif
                        </p>
                    </div>
                    @if($distribusi && $distribusi->status_pengiriman === 'Dalam Perjalanan')
                    <span class="text-[10px] bg-blue-100 text-blue-700 font-bold px-2 py-0.5 rounded-full mt-2 badge-pulse inline-flex items-center">
                        <span class="dot-blink" style="background: #3b82f6; width: 5px; height: 5px; margin-right: 4px;"></span> Aktif
                    </span>
                    @elseif($distribusi && $distribusi->status_pengiriman === 'Selesai')
                    <span class="text-[10px] bg-emerald-100 text-emerald-700 font-bold px-2 py-0.5 rounded-full mt-2">Selesai</span>
                    @endif
                </div>

                {{-- Step 4: Tiba di Sekolah --}}
                <div class="flex items-start group animate-fade-in-left delay-5">
                    <div class="flex flex-col items-center mr-4">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center shadow-md transition-all duration-500
                            {{ ($distribusi && $distribusi->status_pengiriman === 'Selesai') ? 'bg-emerald-500 text-white ring-4 ring-emerald-100' : 'bg-slate-200 text-slate-400' }}">
                            <i class="fas fa-school text-sm"></i>
                        </div>
                    </div>
                    <div class="pt-1.5 flex-1">
                        <p class="font-bold text-sm {{ ($distribusi && $distribusi->status_pengiriman === 'Selesai') ? 'text-slate-800' : 'text-slate-400' }}">Tiba di Sekolah</p>
                        <p class="text-xs text-slate-400 mt-0.5">
                            @if($distribusi && $distribusi->status_pengiriman === 'Selesai')
                                Diterima pukul {{ $distribusi->waktu_tiba ? date('H:i', strtotime($distribusi->waktu_tiba)) . ' WIB' : '-' }}
                            @else
                                Menunggu makanan tiba
                            @endif
                        </p>
                    </div>
                    @if($distribusi && $distribusi->status_pengiriman === 'Selesai')
                    <span class="text-[10px] bg-emerald-100 text-emerald-700 font-bold px-2 py-0.5 rounded-full mt-2">Selesai</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Detail Info Cards --}}
        @if($distribusi)

        @if($distribusi->keterangan_kendala)
        <div class="px-6 pb-6 animate-fade-in-up delay-4">
            <div class="bg-red-50 border border-red-200 rounded-xl p-4 flex items-start card-hover">
                <div class="flex-shrink-0 w-10 h-10 bg-red-100 text-red-600 rounded-full flex items-center justify-center mr-4 badge-pulse">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div>
                    <h4 class="font-bold text-red-800 text-sm mb-1">Pemberitahuan Keterlambatan Pengiriman</h4>
                    <p class="text-sm text-red-600 font-medium">Petugas melaporkan adanya kendala di perjalanan:</p>
                    <p class="text-sm text-red-800 italic mt-1 bg-white px-3 py-2 rounded-lg border border-red-100">"{{ $distribusi->keterangan_kendala }}"</p>
                </div>
            </div>
        </div>
        @endif
        <div class="px-6 pb-6 grid grid-cols-2 gap-3">
            <div class="stat-card bg-gradient-to-br from-blue-50 to-indigo-50 p-4 rounded-xl border border-blue-100/60 animate-fade-in-up delay-3">
                <div class="flex items-center mb-2">
                    <div class="w-8 h-8 bg-blue-500 text-white rounded-lg flex items-center justify-center mr-2 stat-card-icon">
                        <i class="fas fa-box text-xs"></i>
                    </div>
                    <p class="text-[10px] text-blue-400 font-bold uppercase tracking-widest">Total Porsi</p>
                </div>
                <p class="text-2xl font-black text-blue-700 number-animate delay-5">{{ $distribusi->target_porsi }}</p>
            </div>

            <div class="stat-card bg-gradient-to-br from-purple-50 to-pink-50 p-4 rounded-xl border border-purple-100/60 animate-fade-in-up delay-4">
                <div class="flex items-center mb-2">
                    <div class="w-8 h-8 bg-purple-500 text-white rounded-lg flex items-center justify-center mr-2 stat-card-icon">
                        <i class="fas fa-user-shield text-xs"></i>
                    </div>
                    <p class="text-[10px] text-purple-400 font-bold uppercase tracking-widest">Petugas</p>
                </div>
                <p class="text-sm font-bold text-purple-700 truncate">{{ $distribusi->petugas->user->name ?? '-' }}</p>
            </div>

            <div class="stat-card bg-gradient-to-br from-amber-50 to-orange-50 p-4 rounded-xl border border-amber-100/60 animate-fade-in-up delay-5">
                <div class="flex items-center mb-2">
                    <div class="w-8 h-8 bg-amber-500 text-white rounded-lg flex items-center justify-center mr-2 stat-card-icon">
                        <i class="fas fa-utensils text-xs"></i>
                    </div>
                    <p class="text-[10px] text-amber-400 font-bold uppercase tracking-widest">Menu</p>
                </div>
                <p class="text-sm font-bold text-amber-700">{{ $distribusi->menu_hari_ini ?? '-' }}</p>
            </div>

            <div class="stat-card bg-gradient-to-br from-emerald-50 to-teal-50 p-4 rounded-xl border border-emerald-100/60 animate-fade-in-up delay-6">
                <div class="flex items-center mb-2">
                    <div class="w-8 h-8 bg-emerald-500 text-white rounded-lg flex items-center justify-center mr-2 stat-card-icon">
                        <i class="fas fa-check-double text-xs"></i>
                    </div>
                    <p class="text-[10px] text-emerald-400 font-bold uppercase tracking-widest">Diterima</p>
                </div>
                <p class="text-2xl font-black text-emerald-700 number-animate delay-7">{{ $distribusi->porsi_diterima ?? '-' }}</p>
            </div>
        </div>

        {{-- Foto Menu Section --}}
        @if($distribusi->foto_menu)
        <div class="px-6 pb-6 animate-fade-in-up delay-5">
            <div class="bg-slate-50 rounded-xl border border-slate-200 overflow-hidden card-hover">
                <div class="px-4 py-3 border-b border-slate-200 flex items-center">
                    <i class="fas fa-utensils text-blue-500 mr-2"></i>
                    <h4 class="font-bold text-sm text-slate-700">Foto Menu Hari Ini</h4>
                </div>
                <div class="p-4 relative overflow-hidden group">
                    @php
                        $fotoMenuPath = $distribusi->foto_menu;
                        // Fallback jika file fisik tidak ditemukan di server (misal karena redeploy di Railway)
                        if (!file_exists(public_path($fotoMenuPath)) && !str_starts_with($fotoMenuPath, 'http')) {
                            $fotoMenuPath = 'images/default_menu.png';
                        }
                    @endphp
                    <img src="{{ asset($fotoMenuPath) }}"
                         alt="Foto Menu MBG"
                         class="w-full max-h-64 object-cover rounded-lg shadow-sm border border-slate-200 cursor-pointer transition-transform duration-500 group-hover:scale-105"
                         onclick="openImageModal(this.src)"
                         loading="lazy">
                    <div class="absolute inset-4 bg-black/0 group-hover:bg-black/10 transition-all duration-300 rounded-lg flex items-center justify-center">
                        <i class="fas fa-search-plus text-white text-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></i>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- Foto Bukti Section --}}
        @if($distribusi->foto_bukti)
        <div class="px-6 pb-6 animate-fade-in-up delay-6">
            <div class="bg-slate-50 rounded-xl border border-slate-200 overflow-hidden card-hover">
                <div class="px-4 py-3 border-b border-slate-200 flex items-center">
                    <i class="fas fa-camera text-emerald-500 mr-2"></i>
                    <h4 class="font-bold text-sm text-slate-700">Foto Bukti Penerimaan</h4>
                </div>
                <div class="p-4 relative overflow-hidden group">
                    <img src="{{ asset($distribusi->foto_bukti) }}"
                         alt="Bukti Penerimaan MBG"
                         class="w-full rounded-lg shadow-sm border border-slate-200 cursor-pointer transition-transform duration-500 group-hover:scale-105"
                         onclick="openImageModal(this.src)"
                         loading="lazy">
                    <div class="absolute inset-4 bg-black/0 group-hover:bg-black/10 transition-all duration-300 rounded-lg flex items-center justify-center">
                        <i class="fas fa-search-plus text-white text-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></i>
                    </div>
                </div>
            </div>
        </div>
        @endif
        @endif

        {{-- Empty State --}}
        @if(!$distribusi)
        <div class="px-6 py-16 text-center animate-fade-in-up delay-2">
            <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4 animate-float">
                <i class="fas fa-truck text-3xl text-slate-300"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-400">Tidak Ada Pengiriman Hari Ini</h3>
            <p class="text-sm text-slate-400 mt-2 max-w-xs mx-auto">Belum ada jadwal distribusi MBG untuk sekolah Anda pada hari ini.</p>
        </div>
        @endif
    </div>
</div>

{{-- Image Modal --}}
<div id="imageModal" class="fixed inset-0 bg-black/80 z-[100] hidden items-center justify-center p-4" onclick="closeImageModal()">
    <div class="relative max-w-3xl w-full modal-content-animate">
        <button onclick="closeImageModal()" class="absolute -top-10 right-0 text-white text-2xl hover:text-red-400 transition hover:rotate-90 duration-300">
            <i class="fas fa-times"></i>
        </button>
        <img id="modalImage" src="" alt="Preview" class="w-full rounded-xl shadow-2xl">
    </div>
</div>

<script>
    // Animate progress bar on load
    document.addEventListener('DOMContentLoaded', function() {
        const bar = document.getElementById('progressBar');
        if (bar) {
            setTimeout(() => {
                bar.style.width = bar.dataset.target + '%';
            }, 500);
        }
    });

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

    @if($distribusi)
    // Leaflet Map Initialization
    document.addEventListener('DOMContentLoaded', function() {
        if(document.getElementById('map')) {
            // Determine coordinates (Mocking for Demo)
            // Titik A: Dapur Pusat (Pusat Kota)
            const startCoord = [-6.200000, 106.816666]; 
            // Titik B: Sekolah (Agak ke selatan/timur)
            const endCoord = [-6.225000, 106.830000];

            const map = L.map('map', {
                zoomControl: false,
                attributionControl: false,
                dragging: !L.Browser.mobile,
                scrollWheelZoom: false
            });

            // Use CartoDB Positron for a clean, modern GoFood-like look
            L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
                maxZoom: 19
            }).addTo(map);

            // Custom Icons
            const kitchenIcon = L.divIcon({
                className: 'custom-div-icon',
                html: `<div class="w-8 h-8 bg-amber-500 rounded-full border-2 border-white shadow-lg flex items-center justify-center text-white"><i class="fas fa-store text-xs"></i></div>`,
                iconSize: [32, 32],
                iconAnchor: [16, 16]
            });

            const schoolIcon = L.divIcon({
                className: 'custom-div-icon',
                html: `<div class="w-8 h-8 bg-emerald-500 rounded-full border-2 border-white shadow-lg flex items-center justify-center text-white"><i class="fas fa-school text-xs"></i></div>`,
                iconSize: [32, 32],
                iconAnchor: [16, 16]
            });

            const motorIcon = L.divIcon({
                className: 'custom-div-icon',
                html: `<div class="w-10 h-10 bg-blue-600 rounded-full border-2 border-white shadow-xl flex items-center justify-center text-white animate-bounce" style="box-shadow: 0 0 15px rgba(37,99,235,0.4);"><i class="fas fa-truck-pickup text-sm"></i></div>`,
                iconSize: [40, 40],
                iconAnchor: [20, 20]
            });

            // Add Markers
            L.marker(startCoord, {icon: kitchenIcon}).addTo(map).bindPopup('Dapur Pusat MBG');
            L.marker(endCoord, {icon: schoolIcon}).addTo(map).bindPopup('Sekolah Anda');

            // Draw Line (Route simulation)
            const route = L.polyline([startCoord, endCoord], {
                color: '#3b82f6', 
                weight: 4, 
                opacity: 0.6, 
                dashArray: '10, 10',
                lineCap: 'round'
            }).addTo(map);

            map.fitBounds(route.getBounds(), { padding: [40, 40] });

            // Status based simulation
            const status = "{{ $distribusi->status_pengiriman }}";
            let motorMarker;
            
            if(status === 'Belum Dikirim') {
                motorMarker = L.marker(startCoord, {icon: motorIcon}).addTo(map);
            } else if(status === 'Selesai') {
                motorMarker = L.marker(endCoord, {icon: motorIcon}).addTo(map);
            } else if(status === 'Dalam Perjalanan') {
                // Posisi di tengah-tengah perjalanan
                const currentCoord = [
                    startCoord[0] + (endCoord[0] - startCoord[0]) * 0.4,
                    startCoord[1] + (endCoord[1] - startCoord[1]) * 0.4
                ];
                motorMarker = L.marker(currentCoord, {icon: motorIcon}).addTo(map);

                // Simple animation loop
                let progress = 0.4;
                setInterval(() => {
                    if(progress < 0.95) {
                        progress += 0.0005; // speed
                        const newLat = startCoord[0] + (endCoord[0] - startCoord[0]) * progress;
                        const newLng = startCoord[1] + (endCoord[1] - startCoord[1]) * progress;
                        motorMarker.setLatLng([newLat, newLng]);
                    }
                }, 1000);
            }
        }
    });
    @endif
</script>

@endsection
