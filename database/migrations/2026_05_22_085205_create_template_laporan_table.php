<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('template_laporan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_template');
            $table->date('tanggal_upload');
            $table->string('file_path')->nullable();
            $table->string('file_name')->nullable();
            $table->text('deskripsi')->nullable();
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->foreign('admin_id')->references('id')->on('admins')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('template_laporan');
    }
};