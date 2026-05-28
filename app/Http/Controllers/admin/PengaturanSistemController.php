<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PengaturanSistem;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class PengaturanSistemController extends Controller
{
    /**
     * Tampilkan halaman pengaturan sistem
     */
    public function index()
    {
        $pengaturan = PengaturanSistem::aktif();
        return view('pengaturan_sistem', compact('pengaturan'));
    }

    /**
     * Simpan pengaturan sistem
     */
    public function update(Request $request)
    {
        $request->validate([
            'nama_sistem'      => ['required', 'string', 'max:255'],
            'max_upload_mb'    => ['required', 'integer', 'in:5,10,20'],
            'session_timeout'  => ['required', 'integer', 'in:15,30,60'],
            'frekuensi_backup' => ['required', 'in:Harian,Mingguan,Bulanan'],
            'theme'            => ['required', 'in:Light,Dark'],
        ], [
            'nama_sistem.required' => 'Nama sistem wajib diisi.',
        ]);

        $pengaturan = PengaturanSistem::aktif();

        $pengaturan->nama_sistem       = $request->nama_sistem;
        $pengaturan->max_upload_mb     = $request->max_upload_mb;
        $pengaturan->session_timeout   = $request->session_timeout;
        $pengaturan->auto_backup       = $request->boolean('auto_backup');
        $pengaturan->frekuensi_backup  = $request->frekuensi_backup;
        $pengaturan->email_notifikasi  = $request->boolean('email_notifikasi');
        $pengaturan->system_notifikasi = $request->boolean('system_notifikasi');
        $pengaturan->two_factor_auth   = $request->boolean('two_factor_auth');
        $pengaturan->theme             = $request->theme;
        $pengaturan->save();

        ActivityLog::catat('Memperbarui pengaturan sistem', 'Pengaturan Sistem', 'admin');

        return redirect()->route('pengaturan_sistem')
            ->with('success', 'Pengaturan sistem berhasil disimpan.');
    }

    /**
     * Reset pengaturan ke default
     */
    public function reset()
    {
        $pengaturan = PengaturanSistem::aktif();

        $pengaturan->nama_sistem       = 'Sistem Pengarsipan Digital';
        $pengaturan->max_upload_mb     = 10;
        $pengaturan->session_timeout   = 30;
        $pengaturan->auto_backup       = true;
        $pengaturan->frekuensi_backup  = 'Harian';
        $pengaturan->email_notifikasi  = true;
        $pengaturan->system_notifikasi = true;
        $pengaturan->two_factor_auth   = false;
        $pengaturan->theme             = 'Light';
        $pengaturan->save();

        ActivityLog::catat('Mereset pengaturan sistem ke default', 'Pengaturan Sistem', 'admin');

        return redirect()->route('pengaturan_sistem')
            ->with('success', 'Pengaturan berhasil direset ke nilai default.');
    }

    /**
 * Backup database sekarang
 */
public function backup()
{
    try {
        $namaFile  = 'backup_' . now()->format('Y-m-d_H-i-s') . '.sql';
        $path      = storage_path('app/backup/' . $namaFile);

        // Buat folder backup jika belum ada
        if (!file_exists(storage_path('app/backup'))) {
            mkdir(storage_path('app/backup'), 0755, true);
        }

        // Ambil konfigurasi database
        $host     = config('database.connections.sqlite.database');
        $database = config('database.default');

        // Untuk SQLite — copy file database
        if ($database === 'sqlite') {
            $dbPath = config('database.connections.sqlite.database');
            copy($dbPath, $path . '.sqlite');
            $namaFile = $namaFile . '.sqlite';
            $path     = $path . '.sqlite';
        }

        ActivityLog::catat('Melakukan backup database', 'Pengaturan Sistem', 'admin');

        // Download file backup
        return response()->download($path, $namaFile)->deleteFileAfterSend(false);

    } catch (\Exception $e) {
        return redirect()->route('pengaturan_sistem')
            ->with('error', 'Backup gagal: ' . $e->getMessage());
    }
}
}