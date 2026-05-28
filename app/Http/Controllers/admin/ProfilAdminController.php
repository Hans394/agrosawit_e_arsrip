<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfilAdminController extends Controller
{
    /**
     * Tampilkan halaman kelola profil + aktivitas terakhir
     */
    public function index()
    {
        $admin = Auth::guard('admin')->user();

        // Ambil 10 aktivitas terakhir admin ini
        $aktivitas = ActivityLog::where('guard', 'admin')
            ->where('user_id', $admin->id)
            ->latest()
            ->take(10)
            ->get();

        return view('kelola_profil', compact('admin', 'aktivitas'));
    }

    /**
     * Update informasi profil admin
     */
    public function updateProfil(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        $request->validate([
            'name'    => ['required', 'string', 'max:255'],
            'email'   => ['required', 'email', 'unique:admins,email,' . $admin->id],
            'phone'   => ['nullable', 'string', 'max:20'],
            'jabatan' => ['nullable', 'string', 'max:100'],
            'divisi'  => ['nullable', 'string', 'max:100'],
            'alamat'  => ['nullable', 'string', 'max:255'],
            'bio'     => ['nullable', 'string', 'max:500'],
        ], [
            'name.required'  => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email'    => 'Format email tidak valid.',
            'email.unique'   => 'Email sudah digunakan.',
        ]);

        Admin::where('id', $admin->id)->update([
            'name'    => $request->name,
            'email'   => $request->email,
            'phone'   => $request->phone,
            'jabatan' => $request->jabatan,
            'divisi'  => $request->divisi,
            'alamat'  => $request->alamat,
            'bio'     => $request->bio,
        ]);

        // ✅ Catat aktivitas
        ActivityLog::catat('Memperbarui informasi profil', 'Kelola Profil', 'admin');

        return redirect()->route('kelola_profil')
            ->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Ubah password admin
     */
    public function updatePassword(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        $request->validate([
            'current_password'      => ['required', 'string'],
            'password'              => ['required', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => ['required'],
        ], [
            'current_password.required' => 'Password saat ini wajib diisi.',
            'password.required'         => 'Password baru wajib diisi.',
            'password.min'              => 'Password baru minimal 8 karakter.',
            'password.confirmed'        => 'Konfirmasi password tidak cocok.',
        ]);

        if (!Hash::check($request->current_password, $admin->password)) {
            return back()
                ->withErrors(['current_password' => 'Password saat ini tidak sesuai.'])
                ->with('tab', 'password');
        }

        if (Hash::check($request->password, $admin->password)) {
            return back()
                ->withErrors(['password' => 'Password baru tidak boleh sama dengan password lama.'])
                ->with('tab', 'password');
        }

        Admin::where('id', $admin->id)->update([
            'password' => Hash::make($request->password),
        ]);

        // ✅ Catat aktivitas
        ActivityLog::catat('Mengubah password akun', 'Kelola Profil', 'admin');

        return redirect()->route('kelola_profil')
            ->with('success', 'Password berhasil diubah.')
            ->with('tab', 'password');
    }

    /**
     * Tambah akun user baru oleh admin
     */
    public function tambahUser(Request $request)
    {
        $request->validate([
            'name'                  => ['required', 'string', 'max:255'],
            'email'                 => ['required', 'email', 'unique:users,email'],
            'password'              => ['required', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => ['required'],
        ], [
            'name.required'      => 'Nama lengkap wajib diisi.',
            'email.required'     => 'Email wajib diisi.',
            'email.email'        => 'Format email tidak valid.',
            'email.unique'       => 'Email sudah terdaftar.',
            'password.required'  => 'Password wajib diisi.',
            'password.min'       => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $request->phone,    
            'jabatan'  => $request->jabatan,  
            'divisi'   => $request->divisi,   
            'alamat'   => $request->alamat,
            'password' => $request->password,
        ]);

        // ✅ Catat aktivitas
        ActivityLog::catat(
            'Menambahkan akun user baru: ' . $request->name,
            'Kelola Profil',
            'admin'
        );

        return redirect()->route('kelola_profil')
            ->with('success', 'Akun user ' . $request->name . ' berhasil dibuat.')
            ->with('tab', 'tambah-user');
    }
}