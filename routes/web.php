<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\ProfilAdminController;
use App\Http\Controllers\Auth\UserAuthController;
use App\Http\Controllers\Admin\ArsipController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\User\ArsipUserController;
use App\Http\Controllers\User\DashboardUserController;
use App\Http\Controllers\User\ProfilUserController;
use App\Http\Controllers\User\AgendaUserController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\User\LaporanUserController;
use App\Http\Controllers\Admin\AgendaAdminController;
use App\Http\Controllers\Admin\PengaturanSistemController;
use App\Http\Controllers\Admin\ForgotPasswordController;
use App\Http\Controllers\User\SearchUserController;
use App\Http\Controllers\Admin\SearchAdminController;

/*
|--------------------------------------------------------------------------
| ROOT — Redirect otomatis sesuai kondisi login
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    if (Auth::guard('admin')->check()) {
        return redirect()->route('dashboard');
    }
    if (Auth::guard('web')->check()) {
        return redirect()->route('dashboard_user');
    }
    return redirect()->route('user.login');
});

/*
|--------------------------------------------------------------------------
| AUTH ADMIN — Guest (belum login)
|--------------------------------------------------------------------------
*/
Route::middleware('guest:admin')->prefix('admin')->name('admin.')->group(function () {

    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])
        ->name('login');

    Route::post('/login', [AdminAuthController::class, 'login'])
        ->name('login.post');

    Route::get('/register', [AdminAuthController::class, 'showRegisterForm'])
        ->name('register');

    Route::post('/register', [AdminAuthController::class, 'register'])
        ->name('register.post');

    Route::get('/forgot-password', [AdminAuthController::class, 'showForgotPasswordForm'])
        ->name('forgot-password');
    
    // Lupa Password
    Route::get('/forgot-password',
        [ForgotPasswordController::class, 'showForgotForm'])
        ->name('forgot-password');

    Route::post('/forgot-password',
        [ForgotPasswordController::class, 'sendResetLink'])
        ->name('forgot-password.send');

    // Reset Password
    Route::get('/reset-password/{token}',
        [ForgotPasswordController::class, 'showResetForm'])
        ->name('password.reset');

    Route::post('/reset-password',
        [ForgotPasswordController::class, 'resetPassword'])
        ->name('password.reset.post');
});

/*
|--------------------------------------------------------------------------
| AREA ADMIN — Butuh autentikasi admin (guard: admin)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:admin')->group(function () {

    // Logout
    Route::post('/admin/logout', [AdminAuthController::class, 'logout'])
        ->name('admin.logout');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Search Admin (Global)
    Route::get('/admin/search', [SearchAdminController::class, 'index'])
        ->name('search_admin');

    // Kelola Profil — pakai controller (hapus yang closure)
    Route::get('/kelola_profil', [ProfilAdminController::class, 'index'])
        ->name('kelola_profil');

    Route::put('/kelola_profil/update', [ProfilAdminController::class, 'updateProfil'])
        ->name('admin.profil.update');

    Route::put('/kelola_profil/password', [ProfilAdminController::class, 'updatePassword'])
        ->name('admin.profil.password');

    Route::post('/kelola_profil/tambah-user', [ProfilAdminController::class, 'tambahUser'])
        ->name('admin.profil.tambah-user');

    // Halaman lainnya
    Route::get('/data_arsip', fn() => view('data_arsip'))
        ->name('data_arsip');

    Route::get('/input_arsip', fn() => view('input_arsip'))
        ->name('input_arsip');

    Route::get('/buku_agenda', [AgendaAdminController::class, 'index'])
        ->name('buku_agenda');

    Route::post('/buku_agenda', [AgendaAdminController::class, 'store'])
        ->name('agenda_admin.store');

    Route::put('/agenda/{id}', [AgendaAdminController::class, 'update'])
        ->name('agenda_admin.update');

    Route::delete('/agenda/{id}', [AgendaAdminController::class, 'destroy'])
        ->name('agenda_admin.destroy');

    Route::get('/laporan_dan_analisis', fn() => view('laporan_dan_analisis'))
        ->name('laporan_dan_analisis');
    
    Route::get('/laporan_dan_analisis', [LaporanController::class, 'index'])
        ->name('laporan_dan_analisis');

    Route::post('/laporan_dan_analisis', [LaporanController::class, 'store'])
        ->name('laporan.store');

    Route::delete('/laporan/{id}', [LaporanController::class, 'destroy'])
        ->name('laporan.destroy');

    Route::get('/pengaturan_sistem', [PengaturanSistemController::class, 'index'])
        ->name('pengaturan_sistem');

    Route::put('/pengaturan_sistem', [PengaturanSistemController::class, 'update'])
        ->name('pengaturan_sistem.update');

    Route::get('/pengaturan_sistem/backup', [PengaturanSistemController::class, 'backup'])
        ->name('pengaturan_sistem.backup');

    Route::get('/pengaturan_sistem/reset', [PengaturanSistemController::class, 'reset'])
        ->name('pengaturan_sistem.reset');
        
    Route::get('/popup_detail_arsip', fn() => view('popup_detail_arsip'))
        ->name('popup_detail_arsip');
        // Input Arsip
    Route::get('/input_arsip', [ArsipController::class, 'create'])
        ->name('input_arsip');
 
    Route::post('/input_arsip', [ArsipController::class, 'store'])
        ->name('arsip.store');
 
    // Data Arsip
    Route::get('/data_arsip', [ArsipController::class, 'index'])
        ->name('data_arsip');
 
    // Detail Arsip
    Route::get('/arsip/{id}', [ArsipController::class, 'show'])
        ->name('arsip.show');
 
    // Hapus Arsip
    Route::delete('/arsip/{id}', [ArsipController::class, 'destroy'])
        ->name('arsip.destroy');

    Route::put('/arsip/{id}',   [ArsipController::class, 'update'])
        ->name('arsip.update');
});

/*
|--------------------------------------------------------------------------
| AUTH USER — Guest (belum login)
|--------------------------------------------------------------------------
*/
Route::middleware('guest:web')->name('user.')->group(function () {

    Route::get('/login', [UserAuthController::class, 'showLoginForm'])
        ->name('login');

    Route::post('/login', [UserAuthController::class, 'login'])
        ->name('login.post');
});

/*
|--------------------------------------------------------------------------
| AREA USER — Butuh autentikasi user biasa (guard: web)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:web')->group(function () {

    Route::post('/logout', [UserAuthController::class, 'logout'])
        ->name('user.logout');

    Route::get('/dashboard_user', [DashboardUserController::class, 'index'])
        ->name('dashboard_user');

    Route::get('/data_arsip_user',  [ArsipUserController::class, 'index'])
        ->name('data_arsip_user');

    Route::get('/input_arsip_user', fn() => view('input_arsip_user'))
        ->name('input_arsip_user');

    Route::get('/buku_agenda_user', fn() => view('buku_agenda_user'))
        ->name('buku_agenda_user');

    Route::get('/buku_agenda_user',  [AgendaUserController::class, 'index'])
        ->name('buku_agenda_user');

    Route::post('/buku_agenda_user', [AgendaUserController::class, 'store'])
        ->name('agenda_user.store');

    Route::delete('/buku_agenda_user/{id}', [AgendaUserController::class, 'destroy'])
        ->name('agenda_user.destroy');

    Route::get('/laporan_dan_analisis_user', [LaporanUserController::class, 'index'])
        ->name('laporan_dan_analisis_user');

    Route::get('/profil_user', [ProfilUserController::class, 'index'])
        ->name('profil_user');

    Route::get('/input_arsip_user', [ArsipUserController::class, 'create'])
        ->name('input_arsip_user');

    Route::post('/input_arsip_user',[ArsipUserController::class, 'store'])
        ->name('arsip_user.store');

    Route::get('/search', [SearchUserController::class, 'index'])
        ->name('search_user');
});