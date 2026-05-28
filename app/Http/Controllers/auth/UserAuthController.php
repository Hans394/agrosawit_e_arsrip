<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\ActivityLog;

class UserAuthController extends Controller
{
    /**
     * Tampilkan halaman login user
     */
    public function showLoginForm()
    {
        // Jika sudah login, redirect ke dashboard user
        if (Auth::guard('web')->check()) {
            return redirect()->route('dashboard_user');
        }

        return view('login_user');
    }

    /**
     * Proses login user
     */
    public function login(Request $request)
    {
        // Validasi input
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

        // Coba autentikasi dengan guard 'web'
        if (Auth::guard('web')->attempt($credentials, $remember)) {
            $request->session()->regenerate();

            ActivityLog::catat('Login ke sistem', 'Autentikasi', 'web');

            return redirect()->intended(route('dashboard_user'))
                ->with('success', 'Selamat datang, ' . Auth::guard('web')->user()->name . '!');
        }

        // Autentikasi gagal
        throw ValidationException::withMessages([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ]);
    }

    /**
     * Logout user
     */
    public function logout(Request $request)
    {
        ActivityLog::catat('Logout dari sistem', 'Autentikasi', 'web');

        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('user.login')
            ->with('success', 'Anda berhasil logout.');
    }
}