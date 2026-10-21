<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Menampilkan halaman form login web
    public function showLoginForm()
    {
        if (Auth::check()) {
            return $this->redirectByRole();
        }

        return view("auth.login");
    }

    // Memproses submit form login web
    public function login(Request $request)
    {
        $credentials = $request->validate([
            "email"    => "required|email",
            "password" => "required|string",
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return $this->redirectByRole();
        }

        return back()->withErrors([
            "email" => "Kredensial yang diberikan tidak cocok dengan data kami.",
        ])->onlyInput("email");
    }

    // Helper redirect berdasarkan role user
    private function redirectByRole()
    {
        $role = Auth::user()->role;

        if ($role === 'admin') {
            return redirect()->route("admin.dashboard")->with("success", "Selamat datang kembali, Admin!");
        } 
        
        if ($role === 'petugas') {
            return redirect()->route("petugas.peminjaman.index")->with("success", "Selamat datang kembali, Petugas!");
        } 
        
        if ($role === 'peminjam') {
            return redirect()->route("peminjam.katalog")->with("success", "Selamat datang!");
        }

        // Fallback jika role tidak dikenali
        Auth::logout();
        return redirect()->route("login")->withErrors(["email" => "Role pengguna tidak valid atau belum terdaftar."]);
    }

    // Memproses logout web
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route("login")->with("success", "Anda telah berhasil keluar.");
    }
}