@extends('layouts.sekolah')
@section('header_title', 'Konfirmasi Penerimaan MBG')
@section('content')
    <div class="max-w-2xl bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="bg-blue-600 px-6 py-4">
            <h3 class="font-bold text-white text-lg">Form Serah Terima ({{ $data_pengiriman['id_pengiriman'] }})</h3>
        </div>

        <div class="p-6">
            @if(session('error'))
                <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg text-sm text-red-800">
                    <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg text-sm text-red-800">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(!$distribusi)
                <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg text-sm text-yellow-800">
                    <i class="fas fa-info-circle mr-2"></i> Belum ada jadwal pengiriman untuk hari ini, atau pengiriman sudah dikonfirmasi.
                </div>
            @else
                <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg text-sm text-yellow-800">
                    <i class="fas fa-exclamation-triangle mr-2"></i> Pastikan untuk menghitung jumlah porsi sebelum menekan tombol konfirmasi.
                </div>
            @endif

            <form action="{{ route('sekolah.konfirmasi.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Menu Hari Ini</label>
                    <div class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-md text-slate-600 italic">
                        {{ $data_pengiriman['menu_hari_ini'] }}
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Target Porsi</label>
                        <input type="text" disabled value="{{ $data_pengiriman['target_porsi'] }} Porsi"
                            class="w-full px-4 py-2 bg-slate-100 border border-slate-300 rounded-md text-slate-500 font-bold">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Jumlah Diterima Riil <span
                                class="text-red-500">*</span></label>
                        <input type="number" name="jumlah_diterima" required value="{{ old('jumlah_diterima', $data_pengiriman['target_porsi']) }}" min="0" {{ !$distribusi ? 'disabled' : '' }}
                            class="w-full px-4 py-2 border border-blue-400 rounded-md focus:ring-blue-500 focus:border-blue-500 font-bold text-blue-700">
                    </div>
                </div>

                <div class="mb-8">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Upload Foto Bukti Terima <span
                            class="text-red-500">*</span></label>
                    <label for="foto_bukti"
                        class="border-2 border-dashed border-slate-300 rounded-lg p-6 flex justify-center items-center bg-slate-50 hover:bg-slate-100 cursor-pointer transition relative">
                        <input type="file" name="foto_bukti" id="foto_bukti" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" required {{ !$distribusi ? 'disabled' : '' }} onchange="previewFileName(this)">
                        <div class="text-center" id="upload_placeholder">
                            <i class="fas fa-camera text-3xl text-slate-400 mb-2"></i>
                            <p class="text-sm text-slate-500">Klik untuk ambil foto dari kamera HP</p>
                        </div>
                        <div class="text-center hidden" id="upload_preview">
                            <i class="fas fa-image text-3xl text-blue-500 mb-2"></i>
                            <p class="text-sm text-blue-600 font-semibold" id="file_name">Foto terpilih</p>
                            <p class="text-xs text-slate-500 mt-1">Klik untuk mengganti foto</p>
                        </div>
                    </label>
                </div>

                <button type="submit" {{ !$distribusi ? 'disabled' : '' }}
                    class="w-full {{ !$distribusi ? 'bg-slate-400 cursor-not-allowed' : 'bg-blue-600 hover:bg-blue-700' }} text-white font-bold py-3 px-4 rounded-xl shadow-md transition flex justify-center items-center">
                    <i class="fas fa-check-circle mr-2 text-xl"></i> Konfirmasi Pesanan Diterima
                </button>
            </form>
        </div>
    </div>

    <script>
        function previewFileName(input) {
            const placeholder = document.getElementById('upload_placeholder');
            const preview = document.getElementById('upload_preview');
            const fileNameDisplay = document.getElementById('file_name');

            if (input.files && input.files[0]) {
                const fileName = input.files[0].name;
                fileNameDisplay.textContent = fileName;
                placeholder.classList.add('hidden');
                preview.classList.remove('hidden');
            } else {
                placeholder.classList.remove('hidden');
                preview.classList.add('hidden');
            }
        }
    </script>
@endsection