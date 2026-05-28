<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('arsip', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_arsip')->unique();
            $table->date('tanggal_arsip');
            $table->string('judul_dokumen');
            $table->enum('kategori', [
                'SK (Surat Keputusan)',
                'Laporan',
                'Kontrak',
                'Memo',
                'Notulen',
            ]);
            $table->string('file_path')->nullable();
            $table->string('file_name')->nullable();
            $table->text('keterangan')->nullable();
            $table->foreignId('admin_id')->nullable()->constrained('admins')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('arsip');
    }
};