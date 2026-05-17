@extends('layouts.admin')

@section('header_title', 'Titik Distribusi')

@section('content')
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="font-bold text-gray-800 text-lg">Daftar Lokasi Distribusi</h3>
            <a href="{{ route('admin.distribusi.create') }}"
                class="bg-blue-600 text-white px-4 py-2 rounded text-sm font-semibold hover:bg-blue-700 transition inline-flex items-center">
                <i class="fas fa-map-marker-alt mr-2"></i>Tambah Titik
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full whitespace-nowrap text-left">
                <thead class="bg-gray-50 text-gray-600 text-sm uppercase font-semibold">
                    <tr>
                        <th class="px-6 py-3 w-1/5">Sekolah Tujuan</th>
                        <th class="px-6 py-3 w-1/5">Petugas / Kurir</th>
                        <th class="px-6 py-3 w-1/5">Tanggal</th>
                        <th class="px-6 py-3 w-1/5">Target Porsi</th>
                        <th class="px-6 py-3 w-1/5">Status</th>
                        <th class="px-6 py-3 w-1/5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 text-gray-700">
                    @forelse($distribusi as $d)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 font-bold text-gray-800">{{ $d->sekolah->nama_sekolah ?? '-' }}</td>
                            <td class="px-6 py-4">{{ $d->petugas->user->name ?? '-' }}</td>
                            <td class="px-6 py-4">{{ \Carbon\Carbon::parse($d->tanggal)->translatedFormat('d F Y') }}</td>
                            <td class="px-6 py-4 font-medium">{{ $d->target_porsi }} Porsi</td>
                            <td class="px-6 py-4">
                                @if($d->status_pengiriman === 'Belum Dikirim')
                                    <span class="bg-gray-100 text-gray-800 text-xs font-bold px-2.5 py-1 rounded-full border border-gray-400 whitespace-nowrap">
                                        <i class="fas fa-clock mr-1"></i> Belum Dikirim
                                    </span>
                                @elseif($d->status_pengiriman === 'Dalam Perjalanan')
                                    <div class="flex flex-col gap-1">
                                        <span class="bg-blue-100 text-blue-800 text-xs font-bold px-2.5 py-1 rounded-full border border-blue-400 whitespace-nowrap inline-flex items-center">
                                            <i class="fas fa-truck mr-1.5"></i> Sedang Dikirim
                                        </span>
                                        <div class="text-[10px] text-gray-500 font-semibold whitespace-nowrap">
                                            Petugas <i class="fas fa-check-circle text-green-500 mx-0.5"></i> | Sekolah <i class="fas fa-hourglass-half text-yellow-500 ml-0.5"></i>
                                        </div>
                                    </div>
                                @elseif($d->status_pengiriman === 'Selesai')
                                    <div class="flex flex-col gap-1">
                                        <span class="bg-green-100 text-green-800 text-xs font-bold px-2.5 py-1 rounded-full border border-green-400 whitespace-nowrap inline-flex items-center">
                                            <i class="fas fa-check-double mr-1.5"></i> Selesai
                                        </span>
                                        <div class="text-[10px] text-gray-500 font-semibold whitespace-nowrap">
                                            Petugas <i class="fas fa-check-circle text-green-500 mx-0.5"></i> | Sekolah <i class="fas fa-check-circle text-green-500 ml-0.5"></i>
                                        </div>
                                        @if($d->porsi_diterima !== null)
                                            <div class="text-[10px] text-gray-600 mt-0.5 font-bold">
                                                Diterima: {{ $d->porsi_diterima }} Porsi
                                                @if($d->target_porsi > $d->porsi_diterima)
                                                    <span class="text-red-500 block">(Kurang: {{ $d->target_porsi - $d->porsi_diterima }})</span>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <span class="bg-red-100 text-red-800 text-xs font-bold px-2.5 py-1 rounded-full border border-red-400">{{ $d->status_pengiriman }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.distribusi.edit', $d->id) }}" class="text-blue-500 hover:text-blue-700 mr-3" title="Edit Data">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.distribusi.destroy', $d->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus jadwal ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700" title="Hapus Data">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-gray-400">
                                <i class="fas fa-calendar-alt text-3xl mb-2 block"></i>
                                Belum ada jadwal distribusi.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection