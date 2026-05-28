<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengaturan_sistem', function (Blueprint $table) {
            $table->id();

            // Pengaturan Umum
            $table->string('nama_sistem')->default('Sistem Pengarsipan Digital');
            $table->integer('max_upload_mb')->default(10);
            $table->integer('session_timeout')->default(30);

            // Database & Backup
            $table->boolean('auto_backup')->default(true);
            $table->enum('frekuensi_backup', ['Harian', 'Mingguan', 'Bulanan'])->default('Harian');

            // Notifikasi
            $table->boolean('email_notifikasi')->default(true);
            $table->boolean('system_notifikasi')->default(true);

            // Keamanan
            $table->boolean('two_factor_auth')->default(false);

            // Tampilan
            $table->enum('theme', ['Light', 'Dark'])->default('Light');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengaturan_sistem');
    }
};