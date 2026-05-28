<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\ActivityLog;

class AdminAuthController extends Controller
{
    /**
     * Tampilkan halaman login admin
     */
    public function showLoginForm()
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('dashboard');
        }

        return view('login_admin');
    }

    /**
     * Proses login admin
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string', 'min:6'],
        ], [
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
            'password.min'      => 'Password minimal 6 karakter.',
        ]);

        $credentials = $request->only('email', 'password');
        $remember    = $request->boolean('remember');

        if (Auth::guard('admin')->attempt($credentials, $remember)) {
            $request->session()->regenerate();
            ActivityLog::catat('Login ke sistem', 'Autentikasi', 'admin');

            return redirect()->intended(route('dashboard'))
                ->with('success', 'Selamat datang, ' . Auth::guard('admin')->user()->name . '!');
        }

        throw ValidationException::withMessages([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ]);
    }

    /**
     * Logout admin
     */
    public function logout(Request $request)
    {
        ActivityLog::catat('Logout dari sistem', 'Autentikasi', 'admin');
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')
            ->with('success', 'Anda berhasil logout.');
    }

    /**
     * Tampilkan halaman registrasi admin
     */
    public function showRegisterForm()
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('dashboard');
        }

        return view('register');
    }

    /**
     * Proses registrasi admin baru
     */
    public function register(Request $request)
    {
        // Validasi input
        $request->validate([
            'name'                  => ['required', 'string', 'max:255'],
            'email'                 => ['required', 'email', 'unique:admins,email'],
            'password'              => ['required', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => ['required'],
        ], [
            'name.required'         => 'Nama lengkap wajib diisi.',
            'name.max'              => 'Nama maksimal 255 karakter.',
            'email.required'        => 'Email wajib diisi.',
            'email.email'           => 'Format email tidak valid.',
            'email.unique'          => 'Email sudah terdaftar, gunakan email lain.',
            'password.required'     => 'Password wajib diisi.',
            'password.min'          => 'Password minimal 8 karakter.',
            'password.confirmed'    => 'Konfirmasi password tidak cocok.',
            'password_confirmation.required' => 'Konfirmasi password wajib diisi.',
        ]);

        // Buat akun admin baru
        $admin = Admin::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Login otomatis setelah register
        return redirect()->route('admin.login')
            ->with('success', 'Akun berhasil dibuat. Silakan login dengan akun Anda.');
    }

    /**
     * Tampilkan halaman lupa password
     */
    public function showForgotPasswordForm()
    {
        return view('forgot-password');
    }
}
