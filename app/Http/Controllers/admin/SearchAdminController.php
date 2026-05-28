<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Arsip;
use App\Models\Agenda;
use App\Models\TemplateLaporan;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class SearchAdminController extends Controller
{
    /**
     * Cari semua data yang relevan untuk admin
     */
    public function index(Request $request)
    {
        $keyword = $request->input('cari');

        if (!$keyword || trim($keyword) === '') {
            return redirect()->route('dashboard');
        }

        // ─── Cari Arsip ───────────────────────────────────────────
        $arsip = Arsip::with(['admin', 'user'])
            ->where(function ($q) use ($keyword) {
                $q->where('judul_dokumen', 'like', '%' . $keyword . '%')
                  ->orWhere('nomor_arsip',  'like', '%' . $keyword . '%')
                  ->orWhere('kategori',     'like', '%' . $keyword . '%')
                  ->orWhere('keterangan',   'like', '%' . $keyword . '%');
            })
            ->latest()
            ->get();

        // ─── Cari Agenda ──────────────────────────────────────────
        $agenda = Agenda::with(['admin', 'user'])
            ->where(function ($q) use ($keyword) {
                $q->where('judul',     'like', '%' . $keyword . '%')
                  ->orWhere('deskripsi', 'like', '%' . $keyword . '%')
                  ->orWhere('lokasi',    'like', '%' . $keyword . '%')
                  ->orWhere('peserta',   'like', '%' . $keyword . '%');
            })
            ->orderBy('tanggal', 'asc')
            ->get();

        // ─── Cari Template Laporan ────────────────────────────────
        $laporan = TemplateLaporan::with('admin')
            ->where(function ($q) use ($keyword) {
                $q->where('nama_template', 'like', '%' . $keyword . '%')
                  ->orWhere('deskripsi',   'like', '%' . $keyword . '%');
            })
            ->latest()
            ->get();

        // Total hasil
        $totalHasil = $arsip->count() + $agenda->count() + $laporan->count();

        // Catat aktivitas pencarian admin
        ActivityLog::catat(
            'Mencari: ' . $keyword,
            'Pencarian',
            'admin'
        );

        return view('search_admin', compact(
            'keyword',
            'arsip',
            'agenda',
            'laporan',
            'totalHasil'
        ));
    }
}
