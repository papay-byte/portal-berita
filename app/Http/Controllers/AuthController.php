<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Menampilkan form login
    public function showLogin()
    {
        return view('auth.login');
    }

    // Memproses login
    public function login(Request $request)
    {
        $kredensial = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        // attempt(): cek email + password (password dibandingkan dengan hash di database)
        if (Auth::attempt($kredensial, $request->boolean('ingat'))) {
            $request->session()->regenerate(); // ganti ID sesi setelah login (mencegah session fixation)

            // intended(): kembali ke halaman yang tadi dituju sebelum diarahkan ke login
            return redirect()->intended(route('berita.index'));
        }

        // Gagal: kembali ke form, pesan error di kolom email, email lama tetap terisi
        return back()->withErrors(['email' => 'Email atau password salah.'])->onlyInput('email');
    }

    // Logout (harus lewat POST + @csrf)
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();       // hapus sesi lama
        $request->session()->regenerateToken();  // buat token CSRF baru

        return redirect()->route('berita.index');
    }
}