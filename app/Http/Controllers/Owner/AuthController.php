<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Owner;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    // ── LOGIN ──────────────────────────────────────────────────────────
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::guard('owner')->check()) {
            return redirect()->route('owner.dashboard');
        }
        return view('owner.v_auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ], [
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $credentials = $request->only('email', 'password');
        $remember    = $request->boolean('remember');

        if (Auth::guard('owner')->attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return redirect()->route('owner.dashboard')
                ->with('success', 'Selamat datang, ' . Auth::guard('owner')->user()->nama . '!');
        }

        return back()
            ->withInput($request->only('email'))
            ->with('error', 'Email atau password salah. Silakan coba lagi.');
    }

    // ── REGISTER ───────────────────────────────────────────────────────
    public function showRegister(): View|RedirectResponse
    {
        if (Auth::guard('owner')->check()) {
            return redirect()->route('owner.dashboard');
        }
        return view('owner.v_auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $request->validate([
            'nama'          => 'required|string|max:100',
            'email'         => 'required|email|unique:owner,email',
            'phone'         => 'required|string|min:9|max:20',
            'tanggal_lahir' => 'required|date',
            'nik'           => 'nullable|string|size:16',
            'alamat'        => 'nullable|string|max:255',
            'password'      => 'required|min:8|confirmed',
        ], [
            'nama.required'          => 'Nama lengkap wajib diisi.',
            'email.required'         => 'Email wajib diisi.',
            'email.unique'           => 'Email sudah terdaftar.',
            'phone.min'              => 'No. handphone minimal 9 digit.',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
            'nik.size'               => 'NIK harus 16 digit.',
            'password.required'      => 'Password wajib diisi.',
            'password.min'           => 'Password minimal 8 karakter.',
            'password.confirmed'     => 'Konfirmasi password tidak cocok.',
        ]);

        $owner = Owner::create([
            'nama'          => $request->nama,
            'email'         => $request->email,
            'phone'         => $request->phone,
            'tanggal_lahir' => $request->tanggal_lahir,
            'nik'           => $request->nik,
            'alamat'        => $request->alamat,
            'password'      => Hash::make($request->password),
        ]);

        Auth::guard('owner')->login($owner);

        return redirect()->route('owner.dashboard')
            ->with('success', 'Akun owner berhasil dibuat! Selamat datang, ' . $owner->nama . '!');
    }

    // ── LOGOUT ─────────────────────────────────────────────────────────
    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('owner')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('owner.login')
            ->with('success', 'Anda telah berhasil keluar.');
    }
}
