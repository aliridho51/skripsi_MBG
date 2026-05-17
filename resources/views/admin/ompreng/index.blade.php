@extends('layouts.admin')

@section('header_title', 'Logistik Ompreng')

@section('content')
<div class="mb-6 grid grid-cols-2 md:grid-cols-4 gap-4">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Total Dikirim</p>
        <h3 class="text-3xl font-black text-blue-600">{{ $totalDikirim }}</h3>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Total Kembali Utuh</p>
        <h3 class="text-3xl font-black text-emerald-600">{{ $totalKembali }}</h3>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Total Rusak</p>
        <h3 class="text-3xl font-black text-amber-500">{{ $totalRusak }}</h3>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Terindikasi Hilang</p>
        <h3 class="text-3xl font-black text-red-600">{{ $totalHilang }}</h3>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
        <h3 class="font-bold text-gray-800 text-lg">Riwayat Pengembalian Ompreng</h3>
    </div>
    
    <div class="divide-y divide-gray-200">
        @forelse($data_ompreng as $ompreng)
        <div class="p-6 hover:bg-gray-50 transition flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex-1">
                <div class="flex items-center mb-2">
                    <h4 class="font-bold text-gray-800 text-lg mr-3">{{ $ompreng->sekolah->nama_sekolah }}</h4>
                    <span class="px-2.5 py-0.5 rounded text-xs font-bold uppercase tracking-wider
                        {{ $ompreng->kondisi === 'Baik Semua' ? 'bg-emerald-100 text-emerald-800' : ($ompreng->kondisi === 'Banyak Rusak' ? 'bg-red-100 text-red-800' : 'bg-amber-100 text-amber-800') }}">
                        {{ $ompreng->kondisi }}
                    </span>
                </div>
                
                <div class="flex flex-wrap gap-x-6 gap-y-2 text-sm mb-3">
                    <p class="text-gray-600"><i class="fas fa-truck mr-1.5 text-gray-400"></i> Kirim: <strong>{{ $ompreng->jumlah_dikirim }}</strong></p>
                    <p class="text-emerald-600"><i class="fas fa-check mr-1.5"></i> Kembali: <strong>{{ $ompreng->jumlah_kembali }}</strong></p>
                    <p class="text-red-500"><i class="fas fa-times mr-1.5"></i> Rusak/Hilang: <strong>{{ $ompreng->jumlah_dikirim - $ompreng->jumlah_kembali }}</strong></p>
                    <p class="text-gray-500"><i class="far fa-calendar-alt mr-1.5"></i> Ref: {{ $ompreng->distribusi->tanggal->format('d M Y') }}</p>
                </div>
                
                @if($ompreng->catatan)
                    <div class="bg-gray-100 p-3 rounded-lg text-sm text-gray-700 italic inline-block">
                        "{{ $ompreng->catatan }}"
                    </div>
                @endif
            </div>
            
            @if($ompreng->foto_kondisi)
            <div class="flex-shrink-0">
                <img src="{{ asset($ompreng->foto_kondisi) }}" alt="Foto Ompreng" class="w-24 h-24 object-cover rounded-lg shadow-sm border border-gray-200 cursor-pointer hover:opacity-80 transition" onclick="openImageModal(this.src)">
            </div>
            @else
            <div class="flex-shrink-0 w-24 h-24 bg-gray-100 rounded-lg border border-gray-200 flex flex-col items-center justify-center text-gray-400">
                <i class="fas fa-image text-xl mb-1"></i>
                <span class="text-[10px] uppercase font-bold">No Photo</span>
            </div>
            @endif
        </div>
        @empty
        <div class="p-10 text-center text-gray-500">
            <i class="fas fa-box-open text-4xl mb-3 text-gray-300 block"></i>
            Belum ada data pengembalian ompreng yang dicatat oleh sekolah.
        </div>
        @endforelse
    </div>
</div>

{{-- Image Modal --}}
<div id="imageModal" class="fixed inset-0 bg-black/80 z-[100] hidden items-center justify-center p-4" onclick="closeImageModal()">
    <div class="relative max-w-3xl w-full">
        <button onclick="closeImageModal()" class="absolute -top-10 right-0 text-white text-2xl hover:text-red-400 transition">
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
