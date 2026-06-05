<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PenggunaController extends Controller
{
    public function index()
    {
        $pengguna = User::orderBy('role')->get();
        return view('admin.pengguna.index', compact('pengguna'));
    }

    public function create()
    {
        return view('admin.pengguna.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'role'     => 'required|in:admin,petugas,sekolah',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
        ]);

        // Jika role yang dibuat adalah petugas, otomatis buat data profilnya
        if ($user->role === 'petugas') {
            \App\Models\Petugas::create([
                'user_id'      => $user->id,
                'kode_petugas' => 'PTG-' . str_pad($user->id, 4, '0', STR_PAD_LEFT),
                'kendaraan'    => 'Belum Ditentukan',
                'area_tugas'   => 'Belum Ditentukan',
                'status'       => 'Aktif',
            ]);
        }

        // Jika role yang dibuat adalah sekolah, otomatis buat data profilnya
        if ($user->role === 'sekolah') {
            \App\Models\Sekolah::create([
                'user_id'          => $user->id,
                'npsn'             => 'NPSN-' . substr(time(), -6) . $user->id,
                'nama_sekolah'     => $user->name,
                'alamat'           => 'Belum Ditentukan',
                'penanggung_jawab' => $user->name,
                'status'           => 'Aktif',
            ]);
        }

        return redirect()->route('admin.pengguna.index')
            ->with('success', 'Pengguna baru berhasil ditambahkan ke dalam sistem!');
    }

    public function edit(User $user)
    {
        return view('admin.pengguna.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'role'     => 'required|in:admin,petugas,sekolah',
            'password' => 'nullable|min:8',
        ]);

        $user->name  = $request->name;
        $user->email = $request->email;
        $user->role  = $request->role;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        // Jika role diubah ke petugas dan belum ada profilnya, otomatis buat
        if ($user->role === 'petugas' && !$user->petugas()->exists()) {
            \App\Models\Petugas::create([
                'user_id'      => $user->id,
                'kode_petugas' => 'PTG-' . str_pad($user->id, 4, '0', STR_PAD_LEFT),
                'kendaraan'    => 'Belum Ditentukan',
                'area_tugas'   => 'Belum Ditentukan',
                'status'       => 'Aktif',
            ]);
        }

        // Jika role diubah ke sekolah dan belum ada profilnya, otomatis buat
        if ($user->role === 'sekolah' && !$user->sekolah()->exists()) {
            \App\Models\Sekolah::create([
                'user_id'          => $user->id,
                'npsn'             => 'NPSN-' . substr(time(), -6) . $user->id,
                'nama_sekolah'     => $user->name,
                'alamat'           => 'Belum Ditentukan',
                'penanggung_jawab' => $user->name,
                'status'           => 'Aktif',
            ]);
        }

        return redirect()->route('admin.pengguna.index')
            ->with('success', 'Data pengguna "' . $user->name . '" berhasil diperbarui!');
    }

    public function destroy(User $user)
    {
        // Cegah admin menghapus dirinya sendiri jika perlu, 
        // tapi untuk saat ini hapus saja langsung
        $name = $user->name;
        $user->delete();

        return redirect()->route('admin.pengguna.index')
            ->with('success', 'Data pengguna "' . $name . '" berhasil dihapus!');
    }
}