<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sekolah;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PenerimaController extends Controller
{
    public function index()
    {
        $penerima = Sekolah::with('user')->get();
        return view('admin.penerima.index', compact('penerima'));
    }

    public function create()
    {
        return view('admin.penerima.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_sekolah'     => 'required|string|max:100',
            'npsn'             => 'required|string|max:20|unique:sekolah,npsn',
            'alamat'           => 'required|string',
            'penanggung_jawab' => 'required|string|max:100',
            'email'            => 'required|email|unique:users,email',
            'password'         => 'required|min:8',
        ]);

        // Buat akun user untuk sekolah
        $user = User::create([
            'name'     => $request->nama_sekolah,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'sekolah',
        ]);

        // Buat data sekolah
        Sekolah::create([
            'user_id'          => $user->id,
            'npsn'             => $request->npsn,
            'nama_sekolah'     => $request->nama_sekolah,
            'alamat'           => $request->alamat,
            'penanggung_jawab' => $request->penanggung_jawab,
            'status'           => 'Aktif',
        ]);

        return redirect()->route('admin.penerima.index')
            ->with('success', 'Sekolah "' . $request->nama_sekolah . '" berhasil ditambahkan!');
    }

    public function edit(Sekolah $sekolah)
    {
        return view('admin.penerima.edit', compact('sekolah'));
    }

    public function update(Request $request, Sekolah $sekolah)
    {
        $request->validate([
            'nama_sekolah'     => 'required|string|max:100',
            'npsn'             => 'required|string|max:20|unique:sekolah,npsn,' . $sekolah->id,
            'alamat'           => 'required|string',
            'penanggung_jawab' => 'required|string|max:100',
            'status'           => 'required|in:Aktif,Non-Aktif',
            'email'            => 'required|email|unique:users,email,' . $sekolah->user_id,
            'password'         => 'nullable|min:8',
        ]);

        $sekolah->update([
            'npsn'             => $request->npsn,
            'nama_sekolah'     => $request->nama_sekolah,
            'alamat'           => $request->alamat,
            'penanggung_jawab' => $request->penanggung_jawab,
            'status'           => $request->status,
        ]);

        $user = $sekolah->user;
        $user->name = $request->nama_sekolah;
        $user->email = $request->email;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        return redirect()->route('admin.penerima.index')
            ->with('success', 'Data Sekolah "' . $request->nama_sekolah . '" berhasil diperbarui!');
    }

    public function destroy(Sekolah $sekolah)
    {
        $nama = $sekolah->nama_sekolah;
        
        // Hapus akun user, karena cascade atau manual
        if ($sekolah->user) {
            $sekolah->user->delete();
        }
        
        $sekolah->delete();

        return redirect()->route('admin.penerima.index')
            ->with('success', 'Data Sekolah "' . $nama . '" berhasil dihapus!');
    }
}