@extends('layouts.sekolah')
@section('header_title', 'Pengembalian Ompreng')
@section('content')

<div class="max-w-3xl mx-auto space-y-6">

    <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden animate-fade-in-scale">
        <div class="relative overflow-hidden bg-gradient-to-r from-blue-600 via-blue-700 to-indigo-700 px-6 py-6">
            <div class="absolute inset-0 opacity-10">
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-white rounded-full animate-pulse"></div>
                <div class="absolute -bottom-5 -left-5 w-24 h-24 bg-white rounded-full animate-pulse" style="animation-delay: 1s;"></div>
            </div>
            <div class="relative z-10">
                <h2 class="text-white text-xl font-black flex items-center">
                    <i class="fas fa-box-open mr-3"></i> Pengembalian Ompreng
                </h2>
                <p class="text-blue-200 text-sm mt-1">Formulir konfirmasi pengembalian wadah makanan (ompreng)</p>
            </div>
        </div>

        <div class="p-6">
            @if(session('success'))
                <div class="alert-animate mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-sm text-emerald-800 flex items-center">
                    <div class="w-8 h-8 bg-emerald-500 text-white rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                        <i class="fas fa-check"></i>
                    </div>
                    <span class="font-semibold">{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl text-sm text-red-800">
                    {{ session('error') }}
                </div>
            @endif
            @if($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl text-sm text-red-800">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if($distribusi_selesai->isEmpty())
                <div class="text-center py-10 animate-fade-in-up">
                    <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4 animate-float">
                        <i class="fas fa-check-circle text-3xl text-slate-300"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-500">Semua Ompreng Sudah Dikembalikan</h3>
                    <p class="text-sm text-slate-400 mt-2">Tidak ada data pengiriman yang menunggu pengembalian ompreng saat ini.</p>
                </div>
            @else
                <form action="{{ route('sekolah.pengembalian-ompreng.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-5 animate-fade-in-up delay-1">
                        <label class="block text-sm font-bold text-slate-700 mb-2">
                            <i class="fas fa-calendar-check text-blue-500 mr-1"></i> Pilih Pengiriman Terkait
                        </label>
                        <select name="distribusi_id" required class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-200 rounded-xl text-sm text-slate-700 font-medium focus:ring-blue-500 focus:border-blue-500 transition">
                            <option value="">-- Pilih Data Pengiriman --</option>
                            @foreach($distribusi_selesai as $dist)
                                <option value="{{ $dist->id }}" {{ old('distribusi_id') == $dist->id ? 'selected' : '' }}>
                                    {{ $dist->tanggal->format('d/m/Y') }} - {{ $dist->menu_hari_ini ?? 'Tanpa menu' }} (Diterima: {{ $dist->porsi_diterima }} Porsi)
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-5 animate-fade-in-up delay-2">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Jumlah Ompreng Kembali</label>
                            <input type="number" name="jumlah_kembali" value="{{ old('jumlah_kembali') }}" required min="0" class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-200 rounded-xl text-sm text-slate-700 font-medium focus:ring-blue-500 focus:border-blue-500 transition" placeholder="Misal: 100">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Jumlah Ompreng Rusak</label>
                            <input type="number" name="jumlah_rusak" value="{{ old('jumlah_rusak', 0) }}" required min="0" class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-200 rounded-xl text-sm text-slate-700 font-medium focus:ring-blue-500 focus:border-blue-500 transition" placeholder="Misal: 0">
                        </div>
                    </div>

                    <div class="mb-5 animate-fade-in-up delay-3">
                        <label class="block text-sm font-bold text-slate-700 mb-2">
                            <i class="fas fa-clipboard-check text-blue-500 mr-1"></i> Kondisi Ompreng
                        </label>
                        <select name="kondisi" required class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-200 rounded-xl text-sm text-slate-700 font-medium focus:ring-blue-500 focus:border-blue-500 transition">
                            <option value="Baik Semua" {{ old('kondisi') == 'Baik Semua' ? 'selected' : '' }}>Baik Semua</option>
                            <option value="Sebagian Rusak" {{ old('kondisi') == 'Sebagian Rusak' ? 'selected' : '' }}>Sebagian Rusak</option>
                            <option value="Banyak Rusak" {{ old('kondisi') == 'Banyak Rusak' ? 'selected' : '' }}>Banyak Rusak</option>
                        </select>
                    </div>

                    <div class="mb-5 animate-fade-in-up delay-4">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Foto Kondisi Ompreng <span class="text-slate-400 font-normal">(opsional)</span></label>
                        <input type="file" name="foto_kondisi" accept="image/*" class="w-full px-4 py-2 bg-slate-50 border-2 border-slate-200 rounded-xl text-sm text-slate-700 font-medium focus:ring-blue-500 focus:border-blue-500 transition">
                        <p class="text-xs text-slate-400 mt-1">Sangat disarankan jika ada ompreng yang rusak.</p>
                    </div>

                    <div class="mb-6 animate-fade-in-up delay-5">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Catatan Tambahan <span class="text-slate-400 font-normal">(opsional)</span></label>
                        <textarea name="catatan" rows="2" class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-200 rounded-xl text-sm text-slate-700 font-medium focus:ring-blue-500 focus:border-blue-500 transition resize-none placeholder-slate-400" placeholder="Tambahkan catatan jika diperlukan...">{{ old('catatan') }}</textarea>
                    </div>

                    <button type="submit" class="btn-animate btn-ripple w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-black py-3.5 px-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 flex items-center justify-center text-sm uppercase tracking-wider animate-fade-in-up delay-6">
                        <i class="fas fa-save mr-2"></i> Simpan Data Pengembalian
                    </button>
                </form>
            @endif
        </div>
    </div>

    @if($riwayat_pengembalian->count() > 0)
    <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden animate-fade-in-up delay-5 card-hover">
        <div class="px-6 py-4 border-b border-slate-200 bg-slate-50/50">
            <h3 class="font-bold text-slate-800 flex items-center">
                <i class="fas fa-history text-blue-500 mr-2"></i> Riwayat Pengembalian Ompreng
            </h3>
        </div>
        <div class="divide-y divide-slate-100">
            @foreach($riwayat_pengembalian as $riwayat)
            <div class="px-6 py-4 table-row-animate">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-bold text-slate-800 mb-1">
                            Pengiriman: {{ optional($riwayat->distribusi)->tanggal->format('d/m/Y') ?? 'Tidak Diketahui' }}
                        </p>
                        <p class="text-xs text-slate-600 mb-0.5">Dikirim: <strong>{{ $riwayat->jumlah_dikirim }}</strong> | Kembali: <strong>{{ $riwayat->jumlah_kembali }}</strong> | Rusak: <strong>{{ $riwayat->jumlah_rusak }}</strong></p>
                        <p class="text-xs text-slate-600 mb-2">Kondisi: <span class="font-semibold">{{ $riwayat->kondisi }}</span></p>
                        @if($riwayat->catatan)
                            <p class="text-xs text-slate-500 italic bg-white p-2 rounded border border-slate-100 inline-block mb-2">"{{ $riwayat->catatan }}"</p>
                        @endif
                        <p class="text-[10px] text-slate-400 font-medium">
                            <i class="far fa-clock mr-1"></i> Disimpan pada {{ $riwayat->created_at->translatedFormat('d F Y, H:i') }} WIB
                        </p>
                    </div>
                    @if($riwayat->foto_kondisi)
                        <div class="ml-4 flex-shrink-0">
                            <img src="{{ asset($riwayat->foto_kondisi) }}" alt="Foto Ompreng" class="w-16 h-16 object-cover rounded shadow border border-slate-200 cursor-pointer" onclick="openImageModal(this.src)">
                        </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

</div>

{{-- Image Modal --}}
<div id="imageModal" class="fixed inset-0 bg-black/80 z-[100] hidden items-center justify-center p-4 modal-overlay-animate" onclick="closeImageModal()">
    <div class="relative max-w-3xl w-full modal-content-animate">
        <button onclick="closeImageModal()" class="absolute -top-10 right-0 text-white text-2xl hover:text-red-400 transition hover:rotate-90 duration-300">
            <i class="fas fa-times"></i>
        </button>
        <img id="modalImage" src="" alt="Preview" class="w-full rounded-xl shadow-2xl">
    </div>
</div>

<script>
function openImageModal(src) {
    const modal = document.getElementById('imageModal');
    const img = document.getElementById('modalImage');
    img.src = src;
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}
function closeImageModal() {
    const modal = document.getElementById('imageModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}
</script>

@endsection
