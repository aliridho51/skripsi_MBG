<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Distribusi;
use App\Models\Petugas;
use App\Models\Sekolah;
use Illuminate\Http\Request;

class DistribusiController extends Controller
{
    public function index()
    {
        $distribusi = Distribusi::with(['sekolah', 'petugas.user'])->orderByDesc('tanggal')->get();
        return view('admin.distribusi.index', compact('distribusi'));
    }

    public function create()
    {
        $sekolah_list = Sekolah::all();
        $petugas_list = Petugas::with('user')->where('status', 'Aktif')->get();
        return view('admin.distribusi.create', compact('sekolah_list', 'petugas_list'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'sekolah_id'   => 'required|exists:sekolah,id',
            'petugas_id'   => 'required|exists:petugas,id',
            'tanggal'      => 'required|date',
            'target_porsi' => 'required|integer|min:1',
            'menu_hari_ini'=> 'nullable|string',
            'foto_menu'    => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = [
            'sekolah_id'       => $request->sekolah_id,
            'petugas_id'       => $request->petugas_id,
            'tanggal'          => $request->tanggal,
            'target_porsi'     => $request->target_porsi,
            'menu_hari_ini'    => $request->menu_hari_ini,
            'status_pengiriman'=> 'Belum Dikirim',
        ];

        if ($request->hasFile('foto_menu')) {
            $file = $request->file('foto_menu');
            $filename = time() . '_menu_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/foto_menu'), $filename);
            $data['foto_menu'] = 'uploads/foto_menu/' . $filename;
        }

        Distribusi::create($data);

        return redirect()->route('admin.distribusi.index')
            ->with('success', 'Jadwal distribusi berhasil ditambahkan!');
    }

    public function edit(Distribusi $distribusi)
    {
        $sekolah_list = Sekolah::all();
        $petugas_list = Petugas::with('user')->where('status', 'Aktif')->get();
        return view('admin.distribusi.edit', compact('distribusi', 'sekolah_list', 'petugas_list'));
    }

    public function update(Request $request, Distribusi $distribusi)
    {
        $request->validate([
            'sekolah_id'   => 'required|exists:sekolah,id',
            'petugas_id'   => 'required|exists:petugas,id',
            'tanggal'      => 'required|date',
            'target_porsi' => 'required|integer|min:1',
            'menu_hari_ini'=> 'nullable|string',
            'foto_menu'    => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = [
            'sekolah_id'       => $request->sekolah_id,
            'petugas_id'       => $request->petugas_id,
            'tanggal'          => $request->tanggal,
            'target_porsi'     => $request->target_porsi,
            'menu_hari_ini'    => $request->menu_hari_ini,
        ];

        if ($request->hasFile('foto_menu')) {
            $file = $request->file('foto_menu');
            $filename = time() . '_menu_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/foto_menu'), $filename);
            $data['foto_menu'] = 'uploads/foto_menu/' . $filename;
        }

        $distribusi->update($data);

        return redirect()->route('admin.distribusi.index')
            ->with('success', 'Jadwal distribusi berhasil diperbarui!');
    }

    public function destroy(Distribusi $distribusi)
    {
        $distribusi->delete();

        return redirect()->route('admin.distribusi.index')
            ->with('success', 'Jadwal distribusi berhasil dihapus!');
    }
}