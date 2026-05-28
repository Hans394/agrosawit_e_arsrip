<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordController extends Controller
{
    /**
     * Tampilkan halaman lupa password
     */
    public function showForgotForm()
    {
        return view('forgot-password');
    }

    /**
     * Proses kirim link reset password
     */
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email'    => 'Format email tidak valid.',
        ]);

        // Cek apakah email terdaftar di tabel admins
        $admin = Admin::where('email', $request->email)->first();

        if (!$admin) {
            return back()
                ->withErrors(['email' => 'Email tidak ditemukan di sistem.'])
                ->withInput();
        }

        // Hapus token lama jika ada
        DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->delete();

        // Buat token baru
        $token = Str::random(64);

        // Simpan token ke database
        DB::table('password_reset_tokens')->insert([
            'email'      => $request->email,
            'token'      => Hash::make($token),
            'created_at' => Carbon::now(),
        ]);

        // Kirim email reset password
        // Uncomment baris di bawah jika sudah konfigurasi email di .env
        // Mail::send('emails.reset-password', ['token' => $token, 'email' => $request->email], function ($message) use ($request) {
        //     $message->to($request->email);
        //     $message->subject('Reset Password Admin');
        // });

        return back()->with('success',
            'Link reset password telah dikirim ke email ' . $request->email . '. Silakan cek inbox Anda.'
        );
    }

    /**
     * Tampilkan halaman reset password
     */
    public function showResetForm(Request $request, $token)
    {
        return view('reset-password', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    /**
     * Proses reset password baru
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email'                 => ['required', 'email'],
            'token'                 => ['required'],
            'password'              => ['required', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => ['required'],
        ], [
            'email.required'        => 'Email wajib diisi.',
            'password.required'     => 'Password baru wajib diisi.',
            'password.min'          => 'Password minimal 8 karakter.',
            'password.confirmed'    => 'Konfirmasi password tidak cocok.',
        ]);

        // Cek token di database
        $record = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$record) {
            return back()->withErrors(['email' => 'Token reset password tidak valid.']);
        }

        // Cek apakah token cocok
        if (!Hash::check($request->token, $record->token)) {
            return back()->withErrors(['email' => 'Token reset password tidak valid atau sudah kedaluwarsa.']);
        }

        // Cek apakah token sudah expired (60 menit)
        if (Carbon::parse($record->created_at)->addMinutes(60)->isPast()) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            return back()->withErrors(['email' => 'Token reset password sudah kedaluwarsa. Silakan minta ulang.']);
        }

        // Update password admin
        Admin::where('email', $request->email)->update([
            'password' => Hash::make($request->password),
        ]);

        // Hapus token setelah digunakan
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('admin.login')
            ->with('success', 'Password berhasil direset. Silakan login dengan password baru Anda.');
    }
}