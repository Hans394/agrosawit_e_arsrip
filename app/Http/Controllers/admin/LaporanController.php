<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Arsip;
use App\Models\ActivityLog;
use App\Models\TemplateLaporan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class LaporanController extends Controller
{
    /**
     * Tampilkan halaman laporan dan analisis
     */
    public function index()
    {
        $tahunIni   = Carbon::now()->year;
        $bulanIni   = Carbon::now()->month;

        // ─── Statistik ────────────────────────────────────────────
        // Total arsip tahun ini
        $totalArsipTahunIni = Arsip::whereYear('created_at', $tahunIni)->count();

        // Total arsip tahun lalu
        $totalArsipTahunLalu = Arsip::whereYear('created_at', $tahunIni - 1)->count();

        // Persentase kenaikan dari tahun lalu
        $persenKenaikan = $totalArsipTahunLalu > 0
            ? round((($totalArsipTahunIni - $totalArsipTahunLalu) / $totalArsipTahunLalu) * 100)
            : 0;

        // Rata-rata arsip per bulan tahun ini
        $rataRataPerBulan = $bulanIni > 0
            ? round($totalArsipTahunIni / $bulanIni)
            : 0;

        // Efisiensi: persentase arsip yang punya file (sudah lengkap)
        $totalArsip      = Arsip::count();
        $arsipDenganFile = Arsip::whereNotNull('file_path')->count();
        $efisiensi       = $totalArsip > 0
            ? round(($arsipDenganFile / $totalArsip) * 100)
            : 0;

        // ─── Template Laporan ─────────────────────────────────────
        $templateLaporan = TemplateLaporan::with('admin')
            ->latest()
            ->get();

        return view('laporan_dan_analisis', compact(
            'totalArsipTahunIni',
            'persenKenaikan',
            'rataRataPerBulan',
            'efisiensi',
            'templateLaporan'
        ));
    }

    /**
     * Upload template laporan
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_template'  => ['required', 'string', 'max:255'],
            'tanggal_upload' => ['required', 'date'],
            'file'           => ['required', 'file', 'mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png', 'max:10240'],
            'deskripsi'      => ['nullable', 'string', 'max:1000'],
        ], [
            'nama_template.required'  => 'Nama template wajib diisi.',
            'tanggal_upload.required' => 'Tanggal upload wajib diisi.',
            'file.required'           => 'File template wajib diupload.',
            'file.mimes'              => 'Format file harus PDF, DOC, XLS, atau gambar.',
            'file.max'                => 'Ukuran file maksimal 10MB.',
        ]);

        $filePath = null;
        $fileName = null;

        if ($request->hasFile('file')) {
            $file     = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('template_laporan', $fileName, 'public');
        }

        TemplateLaporan::create([
            'nama_template'  => $request->nama_template,
            'tanggal_upload' => $request->tanggal_upload,
            'file_path'      => $filePath,
            'file_name'      => $fileName,
            'deskripsi'      => $request->deskripsi,
            'admin_id'       => Auth::guard('admin')->id(),
        ]);

        ActivityLog::catat(
            'Mengupload template laporan: ' . $request->nama_template,
            'Laporan & Analisis',
            'admin'
        );

        return redirect()->route('laporan_dan_analisis')
            ->with('success', 'Template "' . $request->nama_template . '" berhasil diupload.');
    }

    /**
     * Hapus template laporan
     */
    public function destroy($id)
    {
        $template = TemplateLaporan::findOrFail($id);

        ActivityLog::catat(
            'Menghapus template laporan: ' . $template->nama_template,
            'Laporan & Analisis',
            'admin'
        );

        if ($template->file_path) {
            Storage::disk('public')->delete($template->file_path);
        }

        $template->delete();

        return redirect()->route('laporan_dan_analisis')
            ->with('success', 'Template berhasil dihapus.');
    }
}