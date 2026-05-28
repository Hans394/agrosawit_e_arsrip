<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Arsip;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardUserController extends Controller
{
    public function index()
    {
        $userId = Auth::guard('web')->id();

        // ─── Statistik Kartu ───────────────────────────────────────
        // Total semua arsip (dari admin dan user)
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

        // Aktivitas user hari ini
        $aktivitasHariIni = ActivityLog::where('guard', 'web')
            ->where('user_id', $userId)
            ->whereDate('created_at', Carbon::today())
            ->count();

        // ─── Arsip Terbaru (5 terakhir) ───────────────────────────
        $arsipTerbaruList = Arsip::with(['admin', 'user'])
            ->latest()
            ->take(5)
            ->get();

        // ─── Aktivitas Terakhir user ini (10 terakhir) ────────────
        $aktivitasTerakhir = ActivityLog::where('guard', 'web')
            ->where('user_id', $userId)
            ->latest()
            ->take(10)
            ->get();

        return view('dashboard_user', compact(
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