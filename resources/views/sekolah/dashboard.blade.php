@extends('layouts.sekolah')
@section('header_title', 'Dashboard & Info Kuota')
@section('content')
    <div class="max-w-3xl">
        <div class="bg-white rounded-2xl shadow-sm border-l-8 border-blue-500 p-6 mb-6">
            <h3 class="text-xl font-bold text-slate-800 mb-2">Informasi Pengiriman Hari Ini</h3>
            <p class="text-slate-500 mb-4">{{ $info_hari_ini['tanggal'] }}</p>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-4">
                <div class="bg-slate-50 p-4 rounded-xl text-center border border-slate-100">
                    <p class="text-xs font-bold text-slate-400 uppercase">Total Kuota</p>
                    <p class="text-2xl font-black text-blue-600 mt-1">{{ $info_hari_ini['kuota'] }}</p>
                </div>
                <div class="bg-slate-50 p-4 rounded-xl text-center border border-slate-100 col-span-2">
                    <p class="text-xs font-bold text-slate-400 uppercase">Status Saat Ini</p>
                    <p class="text-lg font-bold text-blue-600 mt-2 animate-pulse">
                        <i class="fas fa-truck mr-2"></i>{{ $info_hari_ini['status'] }}
                    </p>
                </div>
                <div class="bg-slate-50 p-4 rounded-xl text-center border border-slate-100">
                    <p class="text-xs font-bold text-slate-400 uppercase">Estimasi Tiba</p>
                    <p class="text-xl font-bold text-slate-700 mt-1">{{ $info_hari_ini['estimasi_tiba'] }}</p>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-slate-100 flex items-center text-sm text-slate-500">
                <i class="fas fa-user-shield text-blue-500 mr-2"></i>
                Petugas Pengirim: <strong class="ml-1 text-slate-700">{{ $info_hari_ini['petugas'] }}</strong>
            </div>
        </div>
    </div>
@endsection