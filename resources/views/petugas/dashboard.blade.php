@extends('layouts.petugas')

@section('header_title', 'Tugas Hari Ini')

@section('content')
    <!-- Statistik Singkat -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6 flex border-l-4 border-blue-600">
            <div class="flex-1">
                <p class="text-sm text-gray-500 font-semibold uppercase tracking-wider">Tugas Sekarang</p>
                <p class="text-2xl font-bold text-gray-800 mt-2">1 Sekolah</p>
            </div>
            <div class="text-blue-600 text-3xl flex items-center">
                <i class="fas fa-truck-loading"></i>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 flex border-l-4 border-green-600">
            <div class="flex-1">
                <p class="text-sm text-gray-500 font-semibold uppercase tracking-wider">Total Muatan</p>
                <p class="text-2xl font-bold text-gray-800 mt-2">{{ $tugas_aktif['kuota'] ?? 0 }} Porsi</p>
            </div>
            <div class="text-green-600 text-3xl flex items-center">
                <i class="fas fa-box"></i>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 flex border-l-4 border-yellow-500">
            <div class="flex-1">
                <p class="text-sm text-gray-500 font-semibold uppercase tracking-wider">Status Rute</p>
                <p class="text-2xl font-bold text-gray-800 mt-2">Aktif</p>
            </div>
            <div class="text-yellow-500 text-3xl flex items-center">
                <i class="fas fa-route"></i>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 flex border-l-4 border-purple-600">
            <div class="flex-1">
                <p class="text-sm text-gray-500 font-semibold uppercase tracking-wider">Jam Operasional</p>
                <p class="text-2xl font-bold text-gray-800 mt-2">08:00 - Selesai</p>
            </div>
            <div class="text-purple-600 text-3xl flex items-center">
                <i class="fas fa-clock"></i>
            </div>
        </div>
    </div>

    <!-- Detail Tugas Utama -->
    <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden max-w-4xl mx-auto">
        <div class="bg-slate-900 px-8 py-6 flex justify-between items-center border-b border-slate-700">
            <div>
                <h3 class="font-bold text-white text-xl flex items-center">
                    <i class="fas fa-school mr-3 text-blue-500 text-2xl"></i> {{ $tugas_aktif['sekolah'] ?? '-' }}
                </h3>
                <p class="text-slate-400 text-xs mt-1 uppercase tracking-widest font-bold">Destinasi Utama Pengiriman Makanan</p>
            </div>
            <span class="bg-blue-600 text-white text-xs font-bold px-4 py-1.5 rounded-full shadow-lg border border-blue-400 animate-pulse uppercase">
                {{ $tugas_aktif['status'] ?? 'Standby' }}
            </span>
        </div>

        <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-8 bg-white text-slate-700">
            <div class="space-y-6">
                <div class="flex items-start bg-slate-50 p-5 rounded-2xl border border-slate-100 hover:border-blue-200 transition shadow-sm">
                    <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mr-4 shadow-inner">
                        <i class="fas fa-map-marker-alt text-xl"></i>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Alamat Pengantaran</p>
                        <p class="text-slate-800 font-bold text-base mt-0.5 leading-tight">{{ $tugas_aktif['alamat'] ?? '-' }}</p>
                    </div>
                </div>

                <div class="flex items-start bg-slate-50 p-5 rounded-2xl border border-slate-100 hover:border-blue-200 transition shadow-sm">
                    <div class="w-12 h-12 bg-green-100 text-green-600 rounded-full flex items-center justify-center mr-4 shadow-inner">
                        <i class="fas fa-box text-xl"></i>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Jumlah Logistik</p>
                        <p class="text-slate-800 font-black text-2xl mt-0.5">{{ $tugas_aktif['kuota'] ?? 0 }} <span class="text-sm font-medium text-slate-500 italic">Porsi Siap Saji</span></p>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="flex items-start bg-slate-50 p-5 rounded-2xl border border-slate-100 hover:border-blue-200 transition shadow-sm">
                    <div class="w-12 h-12 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center mr-4 shadow-inner">
                        <i class="fas fa-user-check text-xl"></i>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Penanggung Jawab Sekolah</p>
                        <p class="text-slate-800 font-bold text-base mt-0.5">{{ $tugas_aktif['penerima'] ?? '-' }}</p>
                        <p class="text-[11px] text-blue-600 font-bold mt-1 inline-flex items-center hover:underline cursor-pointer">
                            <i class="fas fa-phone-alt mr-1.5"></i> Hubungi Kontak
                        </p>
                    </div>
                </div>

                <div class="bg-blue-50/50 p-5 rounded-2xl border border-blue-100/50">
                    <p class="text-[10px] text-blue-400 font-bold uppercase tracking-widest mb-2">Instruksi Khusus (SOP)</p>
                    <p class="text-blue-800 text-xs italic font-semibold leading-relaxed">"Wajib melakukan dokumentasi foto saat serah terima barang bersama pihak sekolah."</p>
                </div>
            </div>
        </div>

        <div class="bg-slate-50 px-8 py-6 border-t border-slate-200 flex flex-col sm:flex-row gap-4">
            <button class="flex-1 bg-white border border-slate-300 text-slate-700 py-4 rounded-xl font-black text-xs uppercase tracking-widest hover:bg-slate-100 hover:shadow-md transition shadow-sm flex items-center justify-center group">
                <i class="fas fa-map-marked-alt mr-3 text-blue-500 group-hover:scale-110 transition"></i> Buka Navigasi Maps
            </button>
            <button class="flex-1 bg-blue-600 text-white py-4 rounded-xl font-black text-xs uppercase tracking-widest hover:bg-blue-700 hover:shadow-xl transition shadow-lg flex items-center justify-center">
                <i class="fas fa-check-double mr-3 text-lg"></i> Konfirmasi Tiba & Selesai
            </button>
        </div>
    </div>
@endsection