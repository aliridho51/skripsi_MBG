@extends('layouts.admin')

@section('header_title', 'Edit Pengguna')

@section('content')
    <div class="bg-white rounded-lg shadow max-w-3xl mx-auto">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <div>
                <h3 class="font-bold text-gray-800 text-lg">Edit Data Pengguna</h3>
                <p class="text-sm text-gray-500 mt-1">Perbarui informasi akun untuk <strong>{{ $user->name }}</strong>.</p>
            </div>
            <span class="text-xs text-gray-400">ID #{{ $user->id }}</span>
        </div>

        <div class="p-6">
            {{-- Tampilkan error validasi --}}
            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6 text-sm">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Gunakan PUT via method spoofing --}}
            <form action="{{ route('admin.pengguna.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Nama lengkap pengguna">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Alamat Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                            placeholder="email@contoh.com">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Password Baru
                            <span class="text-gray-400 font-normal">(kosongkan jika tidak ingin ganti)</span>
                        </label>
                        <input type="password" name="password"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Minimal 8 karakter">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Hak Akses (Role)</label>
                        <select name="role" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 bg-white">
                            <option value="admin"   {{ old('role', $user->role) == 'admin'   ? 'selected' : '' }}>Admin (Akses Penuh)</option>
                            <option value="petugas" {{ old('role', $user->role) == 'petugas' ? 'selected' : '' }}>Petugas Lapangan (Kurir)</option>
                            <option value="sekolah" {{ old('role', $user->role) == 'sekolah' ? 'selected' : '' }}>Pihak Sekolah (Penerima MBG)</option>
                        </select>
                        <p class="text-xs text-gray-400 mt-1">
                            <i class="fas fa-info-circle mr-1"></i>
                            Mengubah role akan mengubah panel yang dapat diakses pengguna.
                        </p>
                    </div>
                </div>

                <div class="border-t border-gray-200 mt-6 pt-6 flex justify-between items-center">
                    <a href="{{ route('admin.pengguna.index') }}"
                        class="px-4 py-2 bg-white border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 inline-flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </a>
                    <button type="submit"
                        class="px-6 py-2 bg-blue-600 rounded-md text-sm font-medium text-white hover:bg-blue-700 inline-flex items-center shadow">
                        <i class="fas fa-save mr-2"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
