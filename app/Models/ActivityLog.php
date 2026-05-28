<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $table = 'activity_logs';

    protected $fillable = [
        'guard',
        'user_id',
        'nama_user',
        'aktivitas',
        'modul',
        'ip_address',
        'user_agent',
    ];

    /**
     * Simpan log aktivitas — panggil dari controller mana saja
     *
     * Contoh penggunaan:
     *   ActivityLog::catat('Login ke sistem', 'Autentikasi');
     *   ActivityLog::catat('Menambah arsip baru', 'Arsip', 'admin');
     */
    public static function catat(string $aktivitas, string $modul, string $guard = 'admin'): void
    {
        $user = auth($guard)->user();

        if (!$user) return;

        self::create([
            'guard'      => $guard,
            'user_id'    => $user->id,
            'nama_user'  => $user->name,
            'aktivitas'  => $aktivitas,
            'modul'      => $modul,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}