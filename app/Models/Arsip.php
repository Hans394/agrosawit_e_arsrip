<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Arsip extends Model
{
    use HasFactory;

    protected $table = 'arsip';

    protected $fillable = [
        'nomor_arsip',
        'tanggal_arsip',
        'judul_dokumen',
        'kategori',
        'file_path',
        'file_name',
        'keterangan',
        'admin_id',
    ];

    protected $casts = [
        'tanggal_arsip' => 'date',
    ];

    /**
     * Relasi ke model Admin
     */
    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
