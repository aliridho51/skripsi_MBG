<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Menampilkan form login
    public function showLogin()
    {
        // Jika sudah login, langsung lempar ke dashboard masing-masing
        if (Auth::check()) {
            return $this->redirectBerdasarkanRole();
        }
        return view('login');
    }

    // Memproses data login
    public function prosesLogin(Request $request)
    {
        $kredensial = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($kredensial)) {
            $request->session()->regenerate();
            $request->session()->put('auth_time', time());

            // Dapatkan rute default berdasarkan role
            $role = Auth::user()->role;
            $defaultRoute = '/';
            if ($role === 'admin') $defaultRoute = route('admin.dashboard');
            elseif ($role === 'petugas') $defaultRoute = route('petugas.dashboard');
            elseif ($role === 'sekolah') $defaultRoute = route('sekolah.dashboard');

            // Redirect ke halaman yang dituju sebelumnya jika ada, atau ke dashboard
            return redirect()->intended($defaultRoute);
        }

        // Jika salah, kembalikan ke halaman login bawa pesan error
        return back()->withErrors([
            'email' => 'Email atau Password salah.',
        ]);
    }

    // Fungsi logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    // Fungsi pembantu untuk mengarahkan rute sesuai Role
    private function redirectBerdasarkanRole()
    {
        $role = Auth::user()->role;

        // PASTIKAN MENGGUNAKAN == (DUA SAMA DENGAN) ATAU === (TIGA SAMA DENGAN)
        if ($role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($role === 'petugas') {
            return redirect()->route('petugas.dashboard');
        } elseif ($role === 'sekolah') {
            return redirect()->route('sekolah.dashboard');
        }

        Auth::logout();
        return redirect('/login')->withErrors(['email' => 'Role tidak valid.']);
    }
}