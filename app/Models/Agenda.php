<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Agenda extends Model
{
    use HasFactory;

    protected $table = 'agenda';

    protected $fillable = [
        'judul',
        'tanggal',
        'waktu_mulai',
        'waktu_selesai',
        'lokasi',
        'deskripsi',
        'peserta',
        'status',
        'dibuat_oleh',
        'user_id',
        'admin_id',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    /**
     * Relasi ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Admin
     */
    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    /**
     * Update status otomatis berdasarkan tanggal
     */
    public function updateStatus(): void
    {
        $today = Carbon::today();
        $tanggal = Carbon::parse($this->tanggal);

        if ($tanggal->isFuture()) {
            $this->status = 'Akan Datang';
        } elseif ($tanggal->isToday()) {
            $this->status = 'Berlangsung';
        } else {
            $this->status = 'Selesai';
        }

        $this->save();
    }
}