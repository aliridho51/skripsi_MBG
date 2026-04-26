@extends('layouts.admin')

@section('header_title', 'Tambah Jadwal Distribusi')

@section('content')
    <div class="bg-white rounded-lg shadow max-w-3xl mx-auto">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="font-bold text-gray-800 text-lg">Formulir Jadwal Distribusi MBG</h3>
            <p class="text-sm text-gray-500 mt-1">Buat jadwal pengiriman makanan bergizi ke sekolah penerima.</p>
        </div>

        <div class="p-6">
            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6 text-sm">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if($sekolah_list->isEmpty() || $petugas_list->isEmpty())
                <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded-lg mb-6 text-sm flex items-start">
                    <i class="fas fa-exclamation-triangle mr-3 mt-0.5"></i>
                    <div>
                        @if($sekolah_list->isEmpty())
                            <p><strong>Belum ada sekolah terdaftar.</strong> Tambahkan sekolah penerima terlebih dahulu.</p>
                        @endif
                        @if($petugas_list->isEmpty())
                            <p><strong>Belum ada petugas aktif.</strong> Tambahkan petugas melalui menu Pengguna Sistem.</p>
                        @endif
                    </div>
                </div>
            @endif

            <form action="{{ route('admin.distribusi.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sekolah Tujuan</label>
                        <select name="sekolah_id" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 bg-white">
                            <option value="" disabled selected>Pilih sekolah...</option>
                            @foreach($sekolah_list as $s)
                                <option value="{{ $s->id }}" {{ old('sekolah_id') == $s->id ? 'selected' : '' }}>
                                    {{ $s->nama_sekolah }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Petugas / Kurir</label>
                        <select name="petugas_id" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 bg-white">
                            <option value="" disabled selected>Pilih petugas...</option>
                            @foreach($petugas_list as $p)
                                <option value="{{ $p->id }}" {{ old('petugas_id') == $p->id ? 'selected' : '' }}>
                                    {{ $p->user->name ?? '-' }} ({{ $p->kode_petugas }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Distribusi</label>
                        <input type="date" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Target Porsi</label>
                        <input type="number" name="target_porsi" value="{{ old('target_porsi') }}" required min="1"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Contoh: 150">
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Menu Hari Ini <span class="text-gray-400 font-normal">(opsional)</span></label>
                    <textarea name="menu_hari_ini" rows="2"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Contoh: Nasi, Ayam Teriyaki, Sayur Sop, Susu, Buah Pisang">{{ old('menu_hari_ini') }}</textarea>
                </div>

                <div class="border-t border-gray-200 mt-6 pt-6 flex justify-between items-center">
                    <a href="{{ route('admin.distribusi.index') }}"
                        class="px-4 py-2 bg-white border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 inline-flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </a>
                    <button type="submit"
                        class="px-6 py-2 bg-blue-600 rounded-md text-sm font-medium text-white hover:bg-blue-700 inline-flex items-center shadow">
                        <i class="fas fa-calendar-plus mr-2"></i> Buat Jadwal Distribusi
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
