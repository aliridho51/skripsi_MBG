@extends('layouts.admin')

@section('header_title', 'Manajemen Pengguna Sistem')

@section('content')
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="font-bold text-gray-800 text-lg">Daftar Pengguna Sistem</h3>
            <a href="{{ route('admin.pengguna.create') }}"
                class="bg-blue-600 text-white px-4 py-2 rounded text-sm font-semibold hover:bg-blue-700 transition inline-block">
                <i class="fas fa-plus mr-2"></i>Tambah Pengguna
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full whitespace-nowrap text-left">
                <thead class="bg-gray-50 text-gray-600 text-sm uppercase font-semibold">
                    <tr>
                        <th class="px-6 py-3">Nama Lengkap</th>
                        <th class="px-6 py-3">Email</th>
                        <th class="px-6 py-3">Hak Akses (Role)</th>
                        <th class="px-6 py-3">Panel yang Diakses</th>
                        <th class="px-6 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 text-gray-700">
                    @forelse($pengguna as $u)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 font-medium">{{ $u->name }}</td>
                            <td class="px-6 py-4 text-blue-600">{{ $u->email }}</td>
                            <td class="px-6 py-4">
                                @if($u->role === 'admin')
                                    <span class="px-3 py-1 inline-flex text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                                        <i class="fas fa-shield-alt mr-1"></i> Admin
                                    </span>
                                @elseif($u->role === 'petugas')
                                    <span class="px-3 py-1 inline-flex text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                        <i class="fas fa-motorcycle mr-1"></i> Petugas
                                    </span>
                                @elseif($u->role === 'sekolah')
                                    <span class="px-3 py-1 inline-flex text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        <i class="fas fa-school mr-1"></i> Sekolah
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                @if($u->role === 'admin')
                                    <a href="{{ route('admin.dashboard') }}" class="text-purple-600 hover:underline">
                                        <i class="fas fa-external-link-alt mr-1"></i>/admin/dashboard
                                    </a>
                                @elseif($u->role === 'petugas')
                                    <a href="{{ route('petugas.dashboard') }}" class="text-blue-600 hover:underline">
                                        <i class="fas fa-external-link-alt mr-1"></i>/petugas/dashboard
                                    </a>
                                @elseif($u->role === 'sekolah')
                                    <a href="{{ route('sekolah.dashboard') }}" class="text-green-600 hover:underline">
                                        <i class="fas fa-external-link-alt mr-1"></i>/sekolah/dashboard
                                    </a>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('admin.pengguna.edit', $u->id) }}"
                                    class="inline-flex items-center px-3 py-1.5 bg-amber-50 text-amber-700 border border-amber-200 rounded-lg text-xs font-semibold hover:bg-amber-100 transition">
                                    <i class="fas fa-edit mr-1.5"></i> Edit
                                </a>
                                <form action="{{ route('admin.pengguna.destroy', $u->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna {{ $u->name }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-50 text-red-700 border border-red-200 rounded-lg text-xs font-semibold hover:bg-red-100 transition ml-2">
                                        <i class="fas fa-trash mr-1.5"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-gray-400">
                                <i class="fas fa-users text-3xl mb-2 block"></i>
                                Belum ada pengguna terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection