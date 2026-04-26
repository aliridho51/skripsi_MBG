@extends('layouts.admin')

@section('header_title', 'Data Penerima MBG')

@section('content')
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="font-bold text-gray-800 text-lg">Daftar Sekolah Penerima Manfaat</h3>
            <a href="{{ route('admin.penerima.create') }}"
                class="bg-blue-600 text-white px-4 py-2 rounded text-sm font-semibold hover:bg-blue-700 transition inline-flex items-center">
                <i class="fas fa-plus mr-2"></i>Tambah Data Sekolah
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full whitespace-nowrap text-left">
                <thead class="bg-gray-50 text-gray-600 text-sm uppercase font-semibold">
                    <tr>
                        <th class="px-6 py-3 w-1/4">Nama Sekolah</th>
                        <th class="px-6 py-3 w-1/4">Penanggung Jawab (Guru)</th>
                        <th class="px-6 py-3 w-1/4">Total Kuota Siswa</th>
                        <th class="px-6 py-3 w-1/4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 text-gray-700">
                    @forelse($penerima as $p)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 font-bold text-gray-800">{{ $p->nama_sekolah }}</td>
                            <td class="px-6 py-4 font-medium text-blue-600">{{ $p->penanggung_jawab }}</td>
                            <td class="px-6 py-4">{{ $p->status }}</td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.penerima.edit', $p->id) }}" class="text-blue-500 hover:text-blue-700 mr-3" title="Edit Data">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.penerima.destroy', $p->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus sekolah {{ $p->nama_sekolah }}? Semua data terkait (termasuk jadwal distribusi) mungkin akan ikut terhapus.');">
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
                            <td colspan="4" class="px-6 py-10 text-center text-gray-400">
                                <i class="fas fa-school text-3xl mb-2 block"></i>
                                Belum ada data sekolah penerima.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection