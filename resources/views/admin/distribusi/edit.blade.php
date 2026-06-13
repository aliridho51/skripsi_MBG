@extends('layouts.admin')

@section('header_title', 'Edit Jadwal Distribusi')

@section('content')
    <div class="bg-white rounded-lg shadow max-w-3xl mx-auto">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="font-bold text-gray-800 text-lg">Formulir Edit Jadwal Distribusi MBG</h3>
            <p class="text-sm text-gray-500 mt-1">Ubah rincian pengiriman makanan bergizi.</p>
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

            <form action="{{ route('admin.distribusi.update', $distribusi->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sekolah Tujuan</label>
                        <select name="sekolah_id" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 bg-white">
                            @foreach($sekolah_list as $s)
                                <option value="{{ $s->id }}" {{ old('sekolah_id', $distribusi->sekolah_id) == $s->id ? 'selected' : '' }}>
                                    {{ $s->nama_sekolah }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Petugas / Kurir</label>
                        <select name="petugas_id" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 bg-white">
                            @foreach($petugas_list as $p)
                                <option value="{{ $p->id }}" {{ old('petugas_id', $distribusi->petugas_id) == $p->id ? 'selected' : '' }}>
                                    {{ $p->user->name ?? '-' }} ({{ $p->kode_petugas }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Distribusi</label>
                        <input type="date" name="tanggal" value="{{ old('tanggal', $distribusi->tanggal->format('Y-m-d')) }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Target Porsi</label>
                        <input type="number" name="target_porsi" value="{{ old('target_porsi', $distribusi->target_porsi) }}" required min="1"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Menu Hari Ini <span class="text-gray-400 font-normal">(opsional)</span></label>
                    <textarea name="menu_hari_ini" rows="2"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">{{ old('menu_hari_ini', $distribusi->menu_hari_ini) }}</textarea>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Foto Menu <span class="text-gray-400 font-normal">(opsional)</span></label>
                    @php
                        $editFotoSrc = null;
                        if ($distribusi->foto_menu_data) {
                            $editFotoSrc = $distribusi->foto_menu_data;
                        } elseif ($distribusi->foto_menu && file_exists(public_path($distribusi->foto_menu))) {
                            $editFotoSrc = asset($distribusi->foto_menu);
                        }
                    @endphp
                    @if($editFotoSrc)
                    <div class="mb-3 p-3 bg-green-50 rounded-lg border border-green-200">
                        <p class="text-xs text-green-700 mb-2 font-bold"><i class="fas fa-check-circle mr-1"></i>Foto menu saat ini (tetap tersimpan jika tidak upload baru):</p>
                        <img src="{{ $editFotoSrc }}" alt="Foto Menu" class="max-h-48 rounded-lg shadow-sm">
                    </div>
                    @endif
                    <label for="foto_menu" class="border-2 border-dashed border-gray-300 rounded-lg p-6 flex justify-center items-center bg-gray-50 hover:bg-gray-100 cursor-pointer transition relative">
                        <input type="file" name="foto_menu" id="foto_menu" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="previewFotoMenu(this)">
                        <div class="text-center" id="fotoMenuPlaceholder">
                            <i class="fas fa-image text-3xl text-gray-400 mb-2"></i>
                            <p class="text-sm text-gray-500">{{ ($distribusi->foto_menu_data || $distribusi->foto_menu) ? 'Klik untuk mengganti foto' : 'Klik untuk upload foto menu' }}</p>
                            <p class="text-xs text-gray-400 mt-1">Format: JPG, PNG (maks. 20MB)</p>
                        </div>
                        <div class="text-center hidden" id="fotoMenuPreview">
                            <img id="fotoMenuImg" src="" alt="Preview" class="max-h-40 rounded-lg mx-auto mb-2">
                            <p class="text-xs text-blue-600 font-semibold">Klik untuk mengganti foto</p>
                        </div>
                    </label>
                </div>

                <div class="border-t border-gray-200 mt-6 pt-6 flex justify-between items-center">
                    <a href="{{ route('admin.distribusi.index') }}"
                        class="px-4 py-2 bg-white border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 inline-flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </a>
                    <button type="submit"
                        class="px-6 py-2 bg-amber-500 rounded-md text-sm font-medium text-white hover:bg-amber-600 inline-flex items-center shadow">
                        <i class="fas fa-save mr-2"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
    function previewFotoMenu(input) {
        const placeholder = document.getElementById('fotoMenuPlaceholder');
        const preview = document.getElementById('fotoMenuPreview');
        const img = document.getElementById('fotoMenuImg');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                img.src = e.target.result;
                placeholder.classList.add('hidden');
                preview.classList.remove('hidden');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
    </script>
@endsection
