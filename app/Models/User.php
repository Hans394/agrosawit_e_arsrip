<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Guard untuk autentikasi user biasa
     */
    protected $guard = 'web';

    /**
     * Tabel yang digunakan
     */
    protected $table = 'users';

    /**
     * Field yang boleh diisi secara massal
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',    
        'jabatan',  
    'divisi',   
    'alamat',
    ];

    /**
     * Field yang disembunyikan saat serialisasi
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Cast tipe data
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
    ];
}