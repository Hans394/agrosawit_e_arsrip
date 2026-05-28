<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Arsip;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $adminId = Auth::guard('admin')->id();

        // ─── Statistik Kartu ───────────────────────────────────────
        // Total semua arsip
        $totalArsip = Arsip::count();

        // Total arsip bulan ini
        $arsipBulanIni = Arsip::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();

        // Persentase kenaikan arsip dibanding bulan lalu
        $arsipBulanLalu = Arsip::whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->whereYear('created_at', Carbon::now()->subMonth()->year)
            ->count();

        $persenArsip = $arsipBulanLalu > 0
            ? round((($arsipBulanIni - $arsipBulanLalu) / $arsipBulanLalu) * 100)
            : 0;

        // Total arsip terbaru (7 hari terakhir)
        $arsipTerbaru = Arsip::where('created_at', '>=', Carbon::now()->subDays(7))->count();

        // Total kategori unik
        $totalKategori = Arsip::distinct('kategori')->count('kategori');

        // Aktivitas hari ini (semua guard)
        $aktivitasHariIni = ActivityLog::whereDate('created_at', Carbon::today())->count();

        // ─── Arsip Terbaru (5 terakhir) ───────────────────────────
        $arsipTerbaruList = Arsip::with('admin')
            ->latest()
            ->take(5)
            ->get();

        // ─── Aktivitas Terakhir admin ini (10 terakhir) ───────────
        $aktivitasTerakhir = ActivityLog::where('guard', 'admin')
            ->where('user_id', $adminId)
            ->latest()
            ->take(10)
            ->get();

        return view('dashboard', compact(
            'totalArsip',
            'arsipBulanIni',
            'persenArsip',
            'arsipTerbaru',
            'totalKategori',
            'aktivitasHariIni',
            'arsipTerbaruList',
            'aktivitasTerakhir'
        ));
    }
}