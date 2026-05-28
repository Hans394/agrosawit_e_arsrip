<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Arsip;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ArsipController extends Controller
{
    /**
     * Tampilkan semua data arsip dengan filter & pencarian
     */
    public function index(Request $request)
    {
        $query = Arsip::with('admin')->latest();

        // Filter pencarian nama/nomor
        if ($request->filled('cari')) {
            $query->where(function ($q) use ($request) {
                $q->where('judul_dokumen', 'like', '%' . $request->cari . '%')
                  ->orWhere('nomor_arsip', 'like', '%' . $request->cari . '%');
            });
        }

        // Filter kategori
        if ($request->filled('kategori') && $request->kategori !== 'Semua Kategori') {
            $query->where('kategori', $request->kategori);
        }

        // Filter tanggal mulai
        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal_arsip', '>=', $request->tanggal_mulai);
        }

        // Filter tanggal akhir
        if ($request->filled('tanggal_akhir')) {
            $query->whereDate('tanggal_arsip', '<=', $request->tanggal_akhir);
        }

        $arsip      = $query->paginate(5)->withQueryString();
        $totalArsip = Arsip::count();

        return view('data_arsip', compact('arsip', 'totalArsip'));
    }

    /**
     * Tampilkan form input arsip
     */
    public function create()
    {
        return view('input_arsip');
    }

    /**
     * Simpan arsip baru ke database
     */
    public function store(Request $request)
    {
        $request->validate([
            'nomor_arsip'   => ['required', 'string', 'max:50', 'unique:arsip,nomor_arsip'],
            'tanggal_arsip' => ['required', 'date'],
            'judul_dokumen' => ['required', 'string', 'max:255'],
            'kategori'      => ['required', 'string', 'in:SK (Surat Keputusan),Laporan,Kontrak,Memo,Notulen'],
            'file'          => ['nullable', 'file', 'mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png', 'max:10240'],
            'keterangan'    => ['nullable', 'string', 'max:1000'],
        ], [
            'nomor_arsip.required'   => 'Nomor arsip wajib diisi.',
            'nomor_arsip.unique'     => 'Nomor arsip sudah digunakan.',
            'tanggal_arsip.required' => 'Tanggal arsip wajib diisi.',
            'judul_dokumen.required' => 'Judul dokumen wajib diisi.',
            'kategori.required'      => 'Kategori wajib dipilih.',
            'file.mimes'             => 'Format file harus PDF, DOC, XLS, atau gambar.',
            'file.max'               => 'Ukuran file maksimal 10MB.',
        ]);

        $filePath = null;
        $fileName = null;

        if ($request->hasFile('file')) {
            $file     = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('arsip', $fileName, 'public');
        }

        Arsip::create([
            'nomor_arsip'   => $request->nomor_arsip,
            'tanggal_arsip' => $request->tanggal_arsip,
            'judul_dokumen' => $request->judul_dokumen,
            'kategori'      => $request->kategori,
            'file_path'     => $filePath,
            'file_name'     => $fileName,
            'keterangan'    => $request->keterangan,
            'admin_id'      => Auth::guard('admin')->id(),
        ]);

        // ✅ Catat aktivitas menambah arsip
        ActivityLog::catat(
            'Menambahkan arsip baru: ' . $request->judul_dokumen,
            'Input Arsip',
            'admin'
        );

        return redirect()->route('data_arsip')
            ->with('success', 'Arsip "' . $request->judul_dokumen . '" berhasil disimpan.');
    }

    /**
     * Update data arsip
     */
    public function update(Request $request, $id)
    {
        $arsip = Arsip::findOrFail($id);

        $request->validate([
            'nomor_arsip'   => ['required', 'string', 'max:50', 'unique:arsip,nomor_arsip,' . $id],
            'tanggal_arsip' => ['required', 'date'],
            'judul_dokumen' => ['required', 'string', 'max:255'],
            'kategori'      => ['required', 'string', 'in:SK (Surat Keputusan),Laporan,Kontrak,Memo,Notulen'],
            'file'          => ['nullable', 'file', 'mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png', 'max:10240'],
            'keterangan'    => ['nullable', 'string', 'max:1000'],
        ], [
            'nomor_arsip.required'   => 'Nomor arsip wajib diisi.',
            'nomor_arsip.unique'     => 'Nomor arsip sudah digunakan.',
            'tanggal_arsip.required' => 'Tanggal arsip wajib diisi.',
            'judul_dokumen.required' => 'Judul dokumen wajib diisi.',
            'kategori.required'      => 'Kategori wajib dipilih.',
            'file.mimes'             => 'Format file harus PDF, DOC, XLS, atau gambar.',
            'file.max'               => 'Ukuran file maksimal 10MB.',
        ]);

        // Upload file baru jika ada
        if ($request->hasFile('file')) {
            // Hapus file lama
            if ($arsip->file_path) {
                Storage::disk('public')->delete($arsip->file_path);
            }
            $file     = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('arsip', $fileName, 'public');

            $arsip->file_path = $filePath;
            $arsip->file_name = $fileName;
        }

        $arsip->nomor_arsip   = $request->nomor_arsip;
        $arsip->tanggal_arsip = $request->tanggal_arsip;
        $arsip->judul_dokumen = $request->judul_dokumen;
        $arsip->kategori      = $request->kategori;
        $arsip->keterangan    = $request->keterangan;
        $arsip->save();

        // ✅ Catat aktivitas memperbarui arsip
        ActivityLog::catat(
            'Memperbarui arsip: ' . $arsip->judul_dokumen,
            'Data Arsip',
            'admin'
        );

        return redirect()->route('data_arsip')
            ->with('success', 'Arsip "' . $arsip->judul_dokumen . '" berhasil diperbarui.');
    }

    /**
     * Hapus arsip
     */
    public function destroy($id)
    {
        $arsip = Arsip::findOrFail($id);

        // ✅ Catat aktivitas menghapus arsip (sebelum dihapus)
        ActivityLog::catat(
            'Menghapus arsip: ' . $arsip->judul_dokumen,
            'Data Arsip',
            'admin'
        );

        if ($arsip->file_path) {
            Storage::disk('public')->delete($arsip->file_path);
        }

        $arsip->delete();

        return redirect()->route('data_arsip')
            ->with('success', 'Arsip berhasil dihapus.');
    }
}