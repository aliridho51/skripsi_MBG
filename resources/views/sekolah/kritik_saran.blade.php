@extends('layouts.sekolah')
@section('header_title', 'Kritik & Saran')
@section('content')

<div class="max-w-3xl mx-auto space-y-6">

    {{-- Form Kritik & Saran --}}
    <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden animate-fade-in-scale">

        {{-- Header (Blue theme - matching other pages) --}}
        <div class="relative overflow-hidden bg-gradient-to-r from-blue-600 via-blue-700 to-indigo-700 px-6 py-6">
            <div class="absolute inset-0 opacity-10">
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-white rounded-full animate-pulse"></div>
                <div class="absolute -bottom-5 -left-5 w-24 h-24 bg-white rounded-full animate-pulse" style="animation-delay: 1s;"></div>
            </div>
            <div class="relative z-10">
                <h2 class="text-white text-xl font-black flex items-center animate-fade-in-left">
                    <i class="fas fa-comments mr-3"></i> Kritik & Saran
                </h2>
                <p class="text-blue-200 text-sm mt-1 animate-fade-in-left delay-1">Bantu kami meningkatkan kualitas layanan MBG</p>
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

            @if($errors->any())
                <div class="alert-animate mb-6 p-4 bg-red-50 border border-red-200 rounded-xl text-sm text-red-800">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('sekolah.kritik-saran.store') }}" method="POST">
                @csrf

                {{-- Kategori (only Kualitas Makanan & Ketepatan Waktu) --}}
                <div class="mb-5 animate-fade-in-up delay-1">
                    <label class="block text-sm font-bold text-slate-700 mb-2">
                        <i class="fas fa-tag text-blue-500 mr-1"></i> Kategori Umpan Balik
                    </label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="relative cursor-pointer group">
                            <input type="radio" name="kategori" value="Kualitas Makanan" class="peer hidden" {{ old('kategori', 'Kualitas Makanan') === 'Kualitas Makanan' ? 'checked' : '' }}>
                            <div class="peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-600 peer-checked:shadow-lg
                                        bg-slate-50 border-2 border-slate-200 rounded-xl px-4 py-3 text-center text-sm font-bold text-slate-600
                                        hover:border-blue-300 hover:bg-blue-50 transition-all duration-300 group-active:scale-95">
                                <i class="fas fa-utensils block text-lg mb-1 transition-transform duration-300 group-hover:scale-110"></i>
                                Kualitas Makanan
                            </div>
                        </label>
                        <label class="relative cursor-pointer group">
                            <input type="radio" name="kategori" value="Ketepatan Waktu" class="peer hidden" {{ old('kategori') === 'Ketepatan Waktu' ? 'checked' : '' }}>
                            <div class="peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-600 peer-checked:shadow-lg
                                        bg-slate-50 border-2 border-slate-200 rounded-xl px-4 py-3 text-center text-sm font-bold text-slate-600
                                        hover:border-blue-300 hover:bg-blue-50 transition-all duration-300 group-active:scale-95">
                                <i class="fas fa-clock block text-lg mb-1 transition-transform duration-300 group-hover:scale-110"></i>
                                Ketepatan Waktu
                            </div>
                        </label>
                    </div>
                </div>

                {{-- Pilih Distribusi (Optional) --}}
                @if($distribusi_list->count() > 0)
                <div class="mb-5 animate-fade-in-up delay-2">
                    <label class="block text-sm font-bold text-slate-700 mb-2">
                        <i class="fas fa-truck text-blue-500 mr-1"></i> Terkait Pengiriman <span class="text-slate-400 font-normal">(opsional)</span>
                    </label>
                    <select name="distribusi_id"
                        class="input-animate w-full px-4 py-3 bg-slate-50 border-2 border-slate-200 rounded-xl text-sm text-slate-700 font-medium focus:ring-blue-500 focus:border-blue-500 transition">
                        <option value="">-- Tidak terkait pengiriman tertentu --</option>
                        @foreach($distribusi_list as $dist)
                        <option value="{{ $dist->id }}" {{ old('distribusi_id') == $dist->id ? 'selected' : '' }}>
                            {{ $dist->tanggal->format('d/m/Y') }} - {{ $dist->menu_hari_ini ?? 'Tanpa menu' }} ({{ $dist->status_pengiriman }})
                        </option>
                        @endforeach
                    </select>
                </div>
                @endif

                {{-- Komentar --}}
                <div class="mb-6 animate-fade-in-up delay-3">
                    <label class="block text-sm font-bold text-slate-700 mb-2">
                        <i class="fas fa-pen text-blue-500 mr-1"></i> Tulis Kritik & Saran Anda <span class="text-red-500">*</span>
                    </label>
                    <textarea name="komentar" rows="4" required maxlength="1000"
                        class="input-animate w-full px-4 py-3 bg-slate-50 border-2 border-slate-200 rounded-xl text-sm text-slate-700 font-medium
                               focus:ring-blue-500 focus:border-blue-500 transition resize-none placeholder-slate-400"
                        placeholder="Contoh: Makanan hari ini sangat enak dan porsinya cukup. Terima kasih atas pelayanannya..."
                        oninput="updateCharCount(this)">{{ old('komentar') }}</textarea>
                    <div class="flex justify-between mt-1.5">
                        <p class="text-xs text-slate-400">Sampaikan pendapat Anda secara jujur</p>
                        <span id="charCount" class="text-xs font-bold text-slate-400 transition-colors">0/1000</span>
                    </div>
                </div>

                {{-- Submit Button --}}
                <div class="animate-fade-in-up delay-4">
                    <button type="submit"
                        class="btn-animate btn-ripple w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700
                               text-white font-black py-3.5 px-6 rounded-xl shadow-lg
                               flex items-center justify-center text-sm uppercase tracking-wider">
                        <i class="fas fa-paper-plane mr-2"></i> Kirim Umpan Balik
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Riwayat Kritik & Saran --}}
    @if($riwayat_feedback->count() > 0)
    <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden animate-fade-in-up delay-5 card-hover">
        <div class="px-6 py-4 border-b border-slate-200 bg-slate-50/50">
            <h3 class="font-bold text-slate-800 flex items-center">
                <i class="fas fa-history text-blue-500 mr-2"></i> Riwayat Umpan Balik Anda
            </h3>
        </div>

        <div class="divide-y divide-slate-100">
            @foreach($riwayat_feedback as $index => $feedback)
            <div class="px-6 py-4 table-row-animate animate-fade-in-up" style="animation-delay: {{ 0.6 + ($index * 0.08) }}s;">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center space-x-2 mb-1.5">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider transition-all duration-300 hover:scale-105
                                @if($feedback->kategori === 'Kualitas Makanan') bg-blue-100 text-blue-700
                                @elseif($feedback->kategori === 'Ketepatan Waktu') bg-indigo-100 text-indigo-700
                                @else bg-slate-100 text-slate-700
                                @endif
                            ">
                                {{ $feedback->kategori }}
                            </span>
                        </div>
                        <p class="text-sm text-slate-700 leading-relaxed">{{ $feedback->komentar }}</p>
                        <p class="text-[10px] text-slate-400 mt-2 font-medium">
                            <i class="far fa-clock mr-1"></i> {{ $feedback->created_at->translatedFormat('d F Y, H:i') }} WIB
                            @if($feedback->distribusi)
                                &bull; Terkait pengiriman {{ $feedback->distribusi->tanggal->format('d/m/Y') }}
                            @endif
                        </p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

</div>

<script>
    function updateCharCount(el) {
        const count = el.value.length;
        const display = document.getElementById('charCount');
        display.textContent = count + '/1000';
        display.classList.toggle('text-red-500', count > 900);
    }
</script>

@endsection
