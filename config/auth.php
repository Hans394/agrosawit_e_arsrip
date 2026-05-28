<?php

// Tambahkan konfigurasi berikut ke file config/auth.php yang sudah ada
// Cari bagian 'guards' dan 'providers', lalu tambahkan entry berikut:

/*
|--------------------------------------------------------------------------
| TAMBAHKAN KE config/auth.php
|--------------------------------------------------------------------------
|
| Salin dan tempelkan bagian 'admin' ke dalam array yang sesuai
| di file config/auth.php Anda yang sudah ada.
|
*/

return [

    'defaults' => [
        'guard'     => 'web',
        'passwords' => 'users',
    ],

    'guards' => [
        'web' => [
            'driver'   => 'session',
            'provider' => 'users',
        ],

        // ✅ TAMBAHKAN GUARD INI
        'admin' => [
            'driver'   => 'session',
            'provider' => 'admins',
        ],
    ],

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model'  => App\Models\User::class,
        ],

        // ✅ TAMBAHKAN PROVIDER INI
        'admins' => [
            'driver' => 'eloquent',
            'model'  => App\Models\Admin::class,
        ],
    ],

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table'    => 'password_reset_tokens',
            'expire'   => 60,
            'throttle' => 60,
        ],

        // ✅ TAMBAHKAN PASSWORDS INI (opsional, untuk fitur reset password)
        'admins' => [
            'provider' => 'admins',
            'table'    => 'password_reset_tokens',
            'expire'   => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => 10800,

];