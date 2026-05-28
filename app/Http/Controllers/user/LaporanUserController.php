<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Arsip;
use App\Models\TemplateLaporan;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LaporanUserController extends Controller
{
    public function index()
    {
        $tahunIni = Carbon::now()->year;
        $bulanIni = Carbon::now()->month;

        // Total arsip tahun ini
        $totalArsipTahunIni = Arsip::whereYear('created_at', $tahunIni)->count();

        // Total arsip tahun lalu
        $totalArsipTahunLalu = Arsip::whereYear('created_at', $tahunIni - 1)->count();

        // Persentase kenaikan
        $persenKenaikan = $totalArsipTahunLalu > 0
            ? round((($totalArsipTahunIni - $totalArsipTahunLalu) / $totalArsipTahunLalu) * 100)
            : 0;

        // Rata-rata arsip per bulan
        $rataRataPerBulan = $bulanIni > 0
            ? round($totalArsipTahunIni / $bulanIni)
            : 0;

        // Efisiensi kelengkapan file
        $totalArsip      = Arsip::count();
        $arsipDenganFile = Arsip::whereNotNull('file_path')->count();
        $efisiensi       = $totalArsip > 0
            ? round(($arsipDenganFile / $totalArsip) * 100)
            : 0;

        // Template laporan (hanya bisa lihat & download, tidak bisa upload/hapus)
        $templateLaporan = TemplateLaporan::with('admin')
            ->latest()
            ->get();

        // Catat aktivitas
        ActivityLog::catat('Melihat laporan dan analisis', 'Laporan & Analisis', 'web');

        return view('laporan_dan_analisis_user', compact(
            'totalArsipTahunIni',
            'persenKenaikan',
            'rataRataPerBulan',
            'efisiensi',
            'templateLaporan'
        ));
    }
}