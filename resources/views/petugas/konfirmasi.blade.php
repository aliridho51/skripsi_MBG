@extends('layouts.petugas')

@section('header_title', 'Konfirmasi Pengiriman')

@section('content')
    <!-- Detail Tugas Utama -->
    <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden max-w-4xl mx-auto animate-fade-in-scale">
        <div class="bg-slate-900 px-8 py-6 flex justify-between items-center border-b border-slate-700">
            <div>
                <h3 class="font-bold text-white text-xl flex items-center animate-fade-in-left delay-1">
                    <i class="fas fa-school mr-3 text-blue-500 text-2xl"></i> {{ $tugas_aktif ? $tugas_aktif->sekolah->nama_sekolah : '-' }}
                </h3>
                <p class="text-slate-400 text-xs mt-1 uppercase tracking-widest font-bold animate-fade-in-left delay-2">Destinasi Utama Pengiriman Makanan</p>
            </div>
            <span class="bg-blue-600 text-white text-xs font-bold px-4 py-1.5 rounded-full shadow-lg border border-blue-400 uppercase animate-fade-in-right delay-2 {{ $tugas_aktif && $tugas_aktif->status_pengiriman === 'Dalam Perjalanan' ? 'badge-pulse' : '' }}">
                @if($tugas_aktif && $tugas_aktif->status_pengiriman === 'Dalam Perjalanan')
                    <span class="dot-blink" style="background: white;"></span>
                @endif
                {{ $tugas_aktif ? $tugas_aktif->status_pengiriman : 'Standby' }}
            </span>
        </div>

        <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-8 bg-white text-slate-700">
            <div class="space-y-6">
                <div class="flex items-start bg-slate-50 p-5 rounded-2xl border border-slate-100 card-hover shadow-sm animate-fade-in-up delay-2">
                    <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mr-4 shadow-inner stat-card-icon">
                        <i class="fas fa-map-marker-alt text-xl"></i>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Alamat Pengantaran</p>
                        <p class="text-slate-800 font-bold text-base mt-0.5 leading-tight">{{ $tugas_aktif ? $tugas_aktif->sekolah->alamat : '-' }}</p>
                        @if($tugas_aktif)
                            <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($tugas_aktif->sekolah->nama_sekolah . ' ' . $tugas_aktif->sekolah->alamat) }}" target="_blank" class="mt-2 inline-flex items-center text-xs font-bold text-blue-600 hover:text-blue-800 transition btn-animate">
                                <i class="fas fa-location-arrow mr-1.5"></i> Buka Navigasi Maps
                            </a>
                        @endif
                    </div>
                </div>

                <div class="flex items-start bg-slate-50 p-5 rounded-2xl border border-slate-100 card-hover shadow-sm animate-fade-in-up delay-3">
                    <div class="w-12 h-12 bg-green-100 text-green-600 rounded-full flex items-center justify-center mr-4 shadow-inner stat-card-icon">
                        <i class="fas fa-box text-xl"></i>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Jumlah Logistik</p>
                        <p class="text-slate-800 font-black text-2xl mt-0.5 number-animate delay-5">{{ $tugas_aktif ? $tugas_aktif->target_porsi : 0 }} <span class="text-sm font-medium text-slate-500 italic">Porsi Siap Saji</span></p>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="flex items-start bg-slate-50 p-5 rounded-2xl border border-slate-100 card-hover shadow-sm animate-fade-in-up delay-4">
                    <div class="w-12 h-12 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center mr-4 shadow-inner stat-card-icon">
                        <i class="fas fa-user-check text-xl"></i>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Penanggung Jawab Sekolah</p>
                        <p class="text-slate-800 font-bold text-base mt-0.5">{{ $tugas_aktif ? $tugas_aktif->sekolah->penanggung_jawab : '-' }}</p>
                        @if($tugas_aktif)
                            <a href="https://wa.me/?text={{ urlencode('Halo, saya petugas pengiriman MBG menuju ke sekolah Anda.') }}" target="_blank" class="text-[11px] text-green-600 font-bold mt-1 inline-flex items-center hover:underline cursor-pointer btn-animate">
                                <i class="fab fa-whatsapp mr-1.5 text-sm"></i> Hubungi via WhatsApp
                            </a>
                        @endif
                    </div>
                </div>

                <div class="bg-blue-50/50 p-5 rounded-2xl border border-blue-100/50 animate-fade-in-up delay-5">
                    <p class="text-[10px] text-blue-400 font-bold uppercase tracking-widest mb-2">Instruksi Khusus (SOP)</p>
                    <p class="text-blue-800 text-xs italic font-semibold leading-relaxed">"Wajib melakukan dokumentasi foto saat serah terima barang bersama pihak sekolah."</p>
                </div>
            </div>
        </div>

        <div class="bg-slate-50 px-8 py-6 border-t border-slate-200 flex flex-col sm:flex-row gap-4 animate-fade-in-up delay-6">
            @if($tugas_aktif)
                @if($tugas_aktif->status_pengiriman === 'Belum Dikirim')
                    <form action="{{ route('petugas.konfirmasi', $tugas_aktif->id) }}" method="POST" class="flex-1 flex" onsubmit="return confirm('Apakah Anda yakin akan memulai pengiriman ini?');">
                        @csrf
                        <button type="submit" class="btn-animate btn-ripple w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white py-4 rounded-xl font-black text-xs uppercase tracking-widest shadow-lg flex items-center justify-center">
                            <i class="fas fa-truck mr-3 text-lg"></i> Konfirmasi Mulai Pengiriman
                        </button>
                    </form>
                @else
                    <button type="button" onclick="document.getElementById('modalSelesai').classList.remove('hidden')" class="btn-animate btn-ripple w-full bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white py-4 rounded-xl font-black text-xs uppercase tracking-widest shadow-lg flex items-center justify-center">
                        <i class="fas fa-check-circle mr-3 text-lg"></i> Selesaikan Pengiriman
                    </button>
                @endif

                @if($tugas_aktif->status_pengiriman === 'Dalam Perjalanan')
                    <button type="button" onclick="document.getElementById('modalKendala').classList.remove('hidden')" class="btn-animate btn-ripple w-full sm:w-auto bg-red-50 text-red-600 border-2 border-red-200 py-4 px-6 rounded-xl font-black text-xs uppercase tracking-widest hover:bg-red-100 shadow-sm flex items-center justify-center">
                        <i class="fas fa-exclamation-triangle mr-3 text-lg"></i> Lapor Kendala
                    </button>
                @endif
            @else
                <div class="flex-1 text-center py-4 text-gray-500 font-medium animate-fade-in">
                    <i class="fas fa-inbox text-3xl text-gray-300 mb-2 block animate-float"></i>
                    Tidak ada tugas aktif yang perlu dikonfirmasi saat ini.
                </div>
            @endif
        </div>
    </div>

    {{-- Modal Lapor Kendala --}}
    @if($tugas_aktif)
    <div id="modalKendala" class="fixed inset-0 bg-slate-900/60 z-50 hidden flex items-center justify-center p-4 backdrop-blur-sm modal-overlay-animate">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full overflow-hidden modal-content-animate">
            <div class="bg-red-600 px-6 py-4 flex justify-between items-center">
                <h3 class="text-white font-bold text-lg flex items-center">
                    <i class="fas fa-exclamation-triangle mr-2"></i> Lapor Kendala Darurat
                </h3>
                <button type="button" onclick="document.getElementById('modalKendala').classList.add('hidden')" class="text-red-200 hover:text-white transition hover:rotate-90 duration-300">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <form action="{{ route('petugas.lapor-kendala', $tugas_aktif->id) }}" method="POST" class="p-6">
                @csrf
                <p class="text-sm text-slate-600 mb-4">Silakan laporkan kendala yang Anda alami di jalan (misal: ban bocor, cuaca buruk, dll) agar admin dan sekolah mengetahui penyebab keterlambatan.</p>
                
                <textarea name="keterangan_kendala" rows="3" required class="input-animate w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-red-500 focus:border-red-500 text-sm mb-4" placeholder="Tuliskan kendala di sini...">{{ old('keterangan_kendala', $tugas_aktif->keterangan_kendala) }}</textarea>
                
                <button type="submit" class="btn-animate btn-ripple w-full bg-red-600 text-white font-bold py-3 rounded-xl shadow-lg hover:bg-red-700 transition">
                    Kirim Laporan Kendala
                </button>
            </form>
        </div>
    </div>

    {{-- Modal Selesaikan Pengiriman --}}
    <div id="modalSelesai" class="fixed inset-0 bg-slate-900/60 z-50 hidden flex items-center justify-center p-4 backdrop-blur-sm modal-overlay-animate">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full overflow-hidden modal-content-animate">
            <div class="bg-emerald-600 px-6 py-4 flex justify-between items-center">
                <h3 class="text-white font-bold text-lg flex items-center">
                    <i class="fas fa-clipboard-check mr-2"></i> Form Selesai Pengiriman
                </h3>
                <button type="button" onclick="document.getElementById('modalSelesai').classList.add('hidden')" class="text-emerald-200 hover:text-white transition hover:rotate-90 duration-300">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <form action="{{ route('petugas.konfirmasi.selesai', $tugas_aktif->id) }}" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Target Porsi</label>
                    <input type="text" disabled value="{{ $tugas_aktif->target_porsi }}" class="w-full px-4 py-2 bg-slate-100 border border-slate-200 rounded-xl text-slate-500 font-bold">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Jumlah Porsi Diterima <span class="text-red-500">*</span></label>
                    <input type="number" name="jumlah_diterima" required value="{{ $tugas_aktif->target_porsi }}" min="0" max="{{ $tugas_aktif->target_porsi }}" class="w-full px-4 py-2 border border-slate-200 rounded-xl focus:ring-emerald-500 focus:border-emerald-500 font-bold text-slate-700">
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Upload Foto Bukti Serah Terima <span class="text-red-500">*</span></label>
                    <label for="foto_bukti_selesai" class="border-2 border-dashed border-slate-300 rounded-xl p-4 flex justify-center items-center bg-slate-50 hover:bg-emerald-50 hover:border-emerald-300 cursor-pointer transition-all duration-300 relative group">
                        <input type="file" name="foto_bukti" id="foto_bukti_selesai" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" required onchange="previewPetugasFileName(this)">
                        <div class="text-center" id="petugas_upload_placeholder">
                            <i class="fas fa-camera text-2xl text-slate-400 mb-1 group-hover:text-emerald-500 transition-colors group-hover:scale-110 transform duration-300"></i>
                            <p class="text-xs text-slate-500 group-hover:text-emerald-600 transition-colors">Klik untuk ambil foto/kamera HP</p>
                        </div>
                        <div class="text-center hidden" id="petugas_upload_preview">
                            <i class="fas fa-image text-2xl text-emerald-500 mb-1"></i>
                            <p class="text-xs text-emerald-600 font-semibold truncate max-w-[200px]" id="petugas_file_name">Foto terpilih</p>
                            <p class="text-[10px] text-slate-400">Klik untuk mengganti foto</p>
                        </div>
                    </label>
                </div>

                <button type="submit" class="btn-animate btn-ripple w-full bg-emerald-600 text-white font-bold py-3 rounded-xl shadow-lg hover:bg-emerald-700 transition flex items-center justify-center">
                    <i class="fas fa-check-circle mr-2 text-lg"></i> Kirim & Selesaikan Pengiriman
                </button>
            </form>
        </div>
    </div>

    <script>
        function previewPetugasFileName(input) {
            const placeholder = document.getElementById('petugas_upload_placeholder');
            const preview = document.getElementById('petugas_upload_preview');
            const fileNameDisplay = document.getElementById('petugas_file_name');

            if (input.files && input.files[0]) {
                fileNameDisplay.textContent = input.files[0].name;
                placeholder.classList.add('hidden');
                preview.classList.remove('hidden');
            } else {
                placeholder.classList.remove('hidden');
                preview.classList.add('hidden');
            }
        }
    </script>
    @endif

    </div>
@endsection
