<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengaturanSistem extends Model
{
    protected $table = 'pengaturan_sistem';

    protected $fillable = [
        'nama_sistem',
        'max_upload_mb',
        'session_timeout',
        'auto_backup',
        'frekuensi_backup',
        'email_notifikasi',
        'system_notifikasi',
        'two_factor_auth',
        'theme',
    ];

    protected $casts = [
        'auto_backup'        => 'boolean',
        'email_notifikasi'   => 'boolean',
        'system_notifikasi'  => 'boolean',
        'two_factor_auth'    => 'boolean',
    ];

    /**
     * Ambil pengaturan aktif (selalu baris pertama)
     * Jika belum ada, buat dengan nilai default
     */
    public static function aktif(): self
    {
        return self::firstOrCreate(
            ['id' => 1],
            [
                'nama_sistem'        => 'Sistem Pengarsipan Digital',
                'max_upload_mb'      => 10,
                'session_timeout'    => 30,
                'auto_backup'        => true,
                'frekuensi_backup'   => 'Harian',
                'email_notifikasi'   => true,
                'system_notifikasi'  => true,
                'two_factor_auth'    => false,
                'theme'              => 'Light',
            ]
        );
    }
}