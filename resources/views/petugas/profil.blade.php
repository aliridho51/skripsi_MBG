@extends('layouts.petugas')

@section('header_title', 'Profil Petugas')

@section('content')
    <div class="max-w-2xl">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden animate-fade-in-scale">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 h-24 relative overflow-hidden">
                <div class="absolute inset-0 opacity-10">
                    <div class="absolute -top-10 -right-10 w-40 h-40 bg-white rounded-full animate-pulse"></div>
                    <div class="absolute -bottom-5 -left-5 w-24 h-24 bg-white rounded-full animate-pulse" style="animation-delay: 1s;"></div>
                </div>
            </div>

            <div class="px-6 pb-6">
                <div class="flex justify-between items-end -mt-10 mb-4 relative z-10">
                    <div class="w-20 h-20 bg-white rounded-full p-1 shadow-sm avatar-animate">
                        <div
                            class="w-full h-full bg-blue-100 rounded-full flex items-center justify-center text-blue-700 text-3xl font-bold border border-blue-200">
                            S
                        </div>
                    </div>

                    <span class="bg-green-100 text-green-700 text-xs font-bold px-3 py-1 rounded-full mb-2 animate-fade-in delay-2">
                        {{ $petugas->status ?? 'Aktif' }}
                    </span>
                </div>

                <div class="animate-fade-in-up delay-1">
                    <h3 class="text-2xl font-bold text-slate-800">{{ $user->name }}</h3>
                    <p class="text-blue-600 font-medium mt-1">Kode: {{ $petugas->kode_petugas ?? '-' }}</p>
                </div>

                <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6 border-t border-slate-100 pt-6">
                    <div class="stat-card bg-slate-50 p-4 rounded-xl border border-slate-100 animate-fade-in-up delay-2">
                        <div class="flex items-center mb-2">
                            <i class="fas fa-truck text-blue-500 text-xl w-8 stat-card-icon"></i>
                            <span class="text-sm font-bold text-slate-500 uppercase">Kendaraan</span>
                        </div>
                        <p class="font-bold text-slate-800 text-lg ml-8">{{ $petugas->kendaraan ?? '-' }}</p>
                    </div>

                    <div class="space-y-4 animate-fade-in-up delay-3">
                        <div>
                            <p class="text-xs text-slate-500 uppercase font-bold mb-1">Email Sistem / Kontak</p>
                            <p class="font-medium text-slate-800 flex items-center">
                                <i class="fas fa-envelope text-blue-500 text-lg mr-2"></i> {{ $user->email ?? '-' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 uppercase font-bold mb-1">Area Penugasan Utama</p>
                            <p class="font-medium text-slate-800 flex items-center">
                                <i class="fas fa-map-marker-alt text-red-500 text-lg mr-2 px-1"></i>
                                {{ $petugas->area_tugas ?? '-' }}
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection