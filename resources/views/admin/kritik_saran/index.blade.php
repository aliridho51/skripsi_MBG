@extends('layouts.admin')

@section('header_title', 'Umpan Balik Sekolah')

@section('content')
<div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex items-center">
        <div class="w-14 h-14 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-2xl mr-4">
            <i class="fas fa-comments"></i>
        </div>
        <div>
            <p class="text-sm text-gray-500 font-semibold mb-1">Total Umpan Balik</p>
            <h3 class="text-3xl font-black text-gray-800">{{ $totalFeedback }}</h3>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex items-center">
        <div class="w-14 h-14 bg-amber-100 text-amber-500 rounded-full flex items-center justify-center text-2xl mr-4">
            <i class="fas fa-star"></i>
        </div>
        <div>
            <p class="text-sm text-gray-500 font-semibold mb-1">Rata-Rata Rating</p>
            <h3 class="text-3xl font-black text-gray-800">{{ number_format($rataRataRating, 1) }} / 5.0</h3>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex items-center">
        <div class="w-14 h-14 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center text-2xl mr-4">
            <i class="fas fa-chart-pie"></i>
        </div>
        <div>
            <p class="text-sm text-gray-500 font-semibold mb-1">Kategori Terbanyak</p>
            <h3 class="text-lg font-bold text-gray-800">
                {{ $kategoriCount->sortDesc()->keys()->first() ?? 'Belum ada' }}
            </h3>
        </div>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
        <h3 class="font-bold text-gray-800 text-lg">Daftar Kritik & Saran</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-100 text-gray-600 text-sm uppercase tracking-wider">
                    <th class="px-6 py-3 font-semibold border-b">Tanggal & Waktu</th>
                    <th class="px-6 py-3 font-semibold border-b">Sekolah Penerima</th>
                    <th class="px-6 py-3 font-semibold border-b">Kategori & Rating</th>
                    <th class="px-6 py-3 font-semibold border-b w-1/3">Komentar</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($feedbacks as $feedback)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                        {{ $feedback->created_at->format('d/m/Y H:i') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="font-bold text-gray-800">{{ $feedback->sekolah->nama_sekolah }}</div>
                        @if($feedback->distribusi)
                            <div class="text-xs text-gray-500 mt-1">Ref: {{ $feedback->distribusi->tanggal->format('d/m/Y') }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mb-1">
                            {{ $feedback->kategori }}
                        </span>
                        <div class="flex text-amber-400 text-xs">
                            @for($i=1; $i<=5; $i++)
                                <i class="fas fa-star {{ $i <= $feedback->rating ? '' : 'text-gray-300' }}"></i>
                            @endfor
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-700 italic">
                        "{{ $feedback->komentar }}"
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-10 text-center text-gray-500">
                        <i class="fas fa-comment-slash text-4xl mb-3 text-gray-300 block"></i>
                        Belum ada umpan balik dari sekolah.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
