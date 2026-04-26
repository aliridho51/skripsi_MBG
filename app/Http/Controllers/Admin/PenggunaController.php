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

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
        ]);

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