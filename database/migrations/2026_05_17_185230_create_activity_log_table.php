<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->string('guard');           // 'admin' atau 'web'
            $table->unsignedBigInteger('user_id');
            $table->string('nama_user');
            $table->string('aktivitas');       // deskripsi kegiatan
            $table->string('modul');           // modul yang diakses
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};