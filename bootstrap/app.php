<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // 
        $middleware->redirectGuestsTo(function ($request) {
            if ($request->is('admin/*') || $request->is('dashboard') || $request->is('kelola_profil') || $request->is('data_arsip') || $request->is('input_arsip') || $request->is('buku_agenda') || $request->is('laporan_dan_analisis') || $request->is('pengaturan_sistem')) {
                return route('admin.login');
            }
            return route('user.login');
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
