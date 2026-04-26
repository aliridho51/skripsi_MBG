@extends('layouts.admin')

@section('header_title', 'Tambah Sekolah Penerima MBG')

@section('content')
    <div class="bg-white rounded-lg shadow max-w-3xl mx-auto">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="font-bold text-gray-800 text-lg">Formulir Data Sekolah Penerima</h3>
            <p class="text-sm text-gray-500 mt-1">Daftarkan sekolah penerima MBG beserta akun login untuk pihak sekolah.</p>
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

            <form action="{{ route('admin.penerima.store') }}" method="POST">
                @csrf

                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-4 border-b pb-2">Data Sekolah</p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Sekolah</label>
                        <input type="text" name="nama_sekolah" value="{{ old('nama_sekolah') }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Contoh: MIN 4 Darul Ulum">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">NPSN</label>
                        <input type="text" name="npsn" value="{{ old('npsn') }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Contoh: 20501234">
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Alamat Lengkap</label>
                    <textarea name="alamat" rows="2" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Contoh: Wonokerto Selatan, Peterongan, Jombang">{{ old('alamat') }}</textarea>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Penanggung Jawab</label>
                    <input type="text" name="penanggung_jawab" value="{{ old('penanggung_jawab') }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Contoh: Bapak Ahmad Fadli">
                </div>

                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-4 border-b pb-2 mt-8">Akun Login Pihak Sekolah</p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email Login</label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                            placeholder="sekolah@mbg.com">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                        <input type="password" name="password" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Minimal 8 karakter">
                    </div>
                </div>

                <div class="border-t border-gray-200 mt-6 pt-6 flex justify-between items-center">
                    <a href="{{ route('admin.penerima.index') }}"
                        class="px-4 py-2 bg-white border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 inline-flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </a>
                    <button type="submit"
                        class="px-6 py-2 bg-blue-600 rounded-md text-sm font-medium text-white hover:bg-blue-700 inline-flex items-center shadow">
                        <i class="fas fa-save mr-2"></i> Simpan Data Sekolah
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
