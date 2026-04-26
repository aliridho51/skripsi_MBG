@extends('layouts.admin')

@section('header_title', 'Tambah Pengguna Baru')

@section('content')
    <div class="bg-white rounded-lg shadow max-w-3xl mx-auto">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="font-bold text-gray-800 text-lg">Formulir Data Pengguna</h3>
            <p class="text-sm text-gray-500 mt-1">Masukkan informasi detail untuk memberikan akses sistem kepada pengguna baru.</p>
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

            <form action="{{ route('admin.pengguna.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Contoh: Budi Susanto">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Alamat Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                            placeholder="budi@sekolah.com">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Password Akun</label>
                        <input type="password" name="password" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Minimal 8 karakter">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Hak Akses (Role)</label>
                        <select name="role" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 bg-white">
                            <option value="" disabled selected>Pilih hak akses...</option>
                            <option value="admin"   {{ old('role') == 'admin'   ? 'selected' : '' }}>Admin (Akses Penuh)</option>
                            <option value="petugas" {{ old('role') == 'petugas' ? 'selected' : '' }}>Petugas Lapangan (Kurir)</option>
                            <option value="sekolah" {{ old('role') == 'sekolah' ? 'selected' : '' }}>Pihak Sekolah (Penerima MBG)</option>
                        </select>
                        <p class="text-xs text-gray-400 mt-1">
                            <i class="fas fa-info-circle mr-1"></i>
                            Sekolah → Panel Sekolah &nbsp;|&nbsp; Petugas → Panel Petugas &nbsp;|&nbsp; Admin → Panel Admin
                        </p>
                    </div>
                </div>

                <div class="border-t border-gray-200 mt-8 pt-6 flex justify-end space-x-3">
                    <a href="{{ route('admin.pengguna.index') }}"
                        class="px-4 py-2 bg-white border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-blue-700">
                        <i class="fas fa-save mr-2"></i>Simpan Pengguna
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection