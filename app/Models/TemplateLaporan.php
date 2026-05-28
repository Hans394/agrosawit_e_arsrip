<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TemplateLaporan extends Model
{
    use HasFactory;

    protected $table = 'template_laporan';

    protected $fillable = [
        'nama_template',
        'tanggal_upload',
        'file_path',
        'file_name',
        'deskripsi',
        'admin_id',
    ];

    protected $casts = [
        'tanggal_upload' => 'date',
    ];

    /**
     * Relasi ke Admin
     */
    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}