@extends('layouts.petugas')

@section('header_title', 'Tugas Hari Ini')

@section('content')
    <!-- Statistik Singkat -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
        <div class="stat-card bg-white rounded-lg shadow p-6 flex border-l-4 border-blue-600 animate-fade-in-up delay-1">
            <div class="flex-1">
                <p class="text-sm text-gray-500 font-semibold uppercase tracking-wider">Tugas Sekarang</p>
                <p class="text-2xl font-bold text-gray-800 mt-2 number-animate delay-3">{{ $tugas_aktif ? '1 Sekolah' : '0 Sekolah' }}</p>
            </div>
            <div class="text-blue-600 text-3xl flex items-center stat-card-icon">
                <i class="fas fa-truck-loading"></i>
            </div>
        </div>

        <div class="stat-card bg-white rounded-lg shadow p-6 flex border-l-4 border-green-600 animate-fade-in-up delay-2">
            <div class="flex-1">
                <p class="text-sm text-gray-500 font-semibold uppercase tracking-wider">Total Muatan</p>
                <p class="text-2xl font-bold text-gray-800 mt-2 number-animate delay-4">{{ $tugas_aktif ? $tugas_aktif->target_porsi : 0 }} Porsi</p>
            </div>
            <div class="text-green-600 text-3xl flex items-center stat-card-icon">
                <i class="fas fa-box"></i>
            </div>
        </div>

        <div class="stat-card bg-white rounded-lg shadow p-6 flex border-l-4 border-yellow-500 animate-fade-in-up delay-3">
            <div class="flex-1">
                <p class="text-sm text-gray-500 font-semibold uppercase tracking-wider">Status Rute</p>
                <p class="text-2xl font-bold text-gray-800 mt-2 number-animate delay-5">
                    @if($tugas_aktif && $tugas_aktif->status_pengiriman === 'Dalam Perjalanan')
                        <span class="inline-flex items-center">
                            <span class="dot-blink" style="background: #eab308;"></span>
                            {{ $tugas_aktif->status_pengiriman }}
                        </span>
                    @else
                        {{ $tugas_aktif ? $tugas_aktif->status_pengiriman : 'Tidak Aktif' }}
                    @endif
                </p>
            </div>
            <div class="text-yellow-500 text-3xl flex items-center stat-card-icon">
                <i class="fas fa-route"></i>
            </div>
        </div>

        <div class="stat-card bg-white rounded-lg shadow p-6 flex border-l-4 border-purple-600 animate-fade-in-up delay-4">
            <div class="flex-1">
                <p class="text-sm text-gray-500 font-semibold uppercase tracking-wider">Jam Operasional</p>
                <p class="text-2xl font-bold text-gray-800 mt-2 number-animate delay-6">08:00 - Selesai</p>
            </div>
            <div class="text-purple-600 text-3xl flex items-center stat-card-icon">
                <i class="fas fa-clock"></i>
            </div>
        </div>
    </div>

@endsection