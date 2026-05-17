@extends('layouts.sekolah')
@section('header_title', 'Profil Instansi Penerima')
@section('content')
    <div class="max-w-2xl bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden animate-fade-in-scale">
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 h-24 relative overflow-hidden">
            <div class="absolute inset-0 opacity-10">
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-white rounded-full animate-pulse"></div>
                <div class="absolute -bottom-5 -left-5 w-24 h-24 bg-white rounded-full animate-pulse" style="animation-delay: 1s;"></div>
            </div>
        </div>
        <div class="px-6 pb-6">
            <div class="flex justify-between items-end -mt-10 mb-4 relative z-10">
                <div class="w-20 h-20 bg-white rounded-full p-1 shadow-sm avatar-animate">
                    <div class="w-full h-full bg-blue-100 rounded-full flex items-center justify-center text-blue-700 text-3xl font-bold border border-blue-200">
                        <i class="fas fa-school"></i>
                    </div>
                </div>
                <span class="bg-blue-100 text-blue-700 text-xs font-bold px-3 py-1 rounded-full mb-2 border border-blue-300 animate-fade-in delay-2">NPSN: {{ $sekolah->npsn ?? '-' }}</span>
            </div>
            <div class="animate-fade-in-up delay-1">
                <h3 class="text-2xl font-bold text-slate-800">{{ $sekolah->nama_sekolah ?? 'Nama Sekolah Belum Diatur' }}</h3>
                <p class="text-slate-500 mt-1"><i class="fas fa-map-marker-alt text-red-500 mr-2"></i>{{ $sekolah->alamat ?? '-' }}</p>
            </div>
            <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 gap-4 border-t border-slate-100 pt-6">
                <div class="stat-card bg-slate-50 p-4 rounded-lg border border-slate-100 animate-fade-in-up delay-2">
                    <p class="text-xs font-bold text-slate-400 uppercase mb-1">Status Instansi</p>
                    <p class="font-bold text-slate-700">
                        @if(($sekolah->status ?? 'Non-Aktif') === 'Aktif')
                            <span class="text-green-600"><i class="fas fa-check-circle mr-1"></i> Aktif</span>
                        @else
                            <span class="text-red-500"><i class="fas fa-times-circle mr-1"></i> Non-Aktif</span>
                        @endif
                    </p>
                </div>
                <div class="stat-card bg-slate-50 p-4 rounded-lg border border-slate-100 animate-fade-in-up delay-3">
                    <p class="text-xs font-bold text-slate-400 uppercase mb-1">Penanggung Jawab MBG</p>
                    <p class="font-bold text-slate-700">{{ $sekolah->penanggung_jawab ?? '-' }}</p>
                </div>
                <div class="stat-card bg-slate-50 p-4 rounded-lg border border-slate-100 sm:col-span-2 animate-fade-in-up delay-4">
                    <p class="text-xs font-bold text-slate-400 uppercase mb-1">Email Sistem / Kontak</p>
                    <p class="font-bold text-slate-700"><i class="fas fa-envelope text-blue-500 mr-2"></i>{{ $user->email ?? '-' }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection