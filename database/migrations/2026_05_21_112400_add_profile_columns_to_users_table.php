<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->string('phone')->nullable()->after('email');
        $table->string('jabatan')->nullable()->after('phone');
        $table->string('divisi')->nullable()->after('jabatan');
        $table->string('alamat')->nullable()->after('divisi');
    });
}

public function down(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn(['phone', 'jabatan', 'divisi', 'alamat']);
    });
}
};
