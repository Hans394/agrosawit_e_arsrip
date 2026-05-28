<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agenda;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AgendaAdminController extends Controller
{
    /**
     * Tampilkan semua agenda
     */
    public function index()
    {
        $agenda = Agenda::with(['user', 'admin'])->orderBy('tanggal', 'asc')->get();

        // Update status otomatis berdasarkan tanggal
        foreach ($agenda as $item) {
            $item->updateStatus();
        }

        $agendaMendatang = Agenda::where('status', 'Akan Datang')->count();
        $agendaSelesai   = Agenda::where('status', 'Selesai')->count();
        $totalAgenda     = Agenda::count();

        return view('buku_agenda', compact(
            'agenda',
            'agendaMendatang',
            'agendaSelesai',
            'totalAgenda'
        ));
    }

    /**
     * Simpan agenda baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul'         => ['required', 'string', 'max:255'],
            'tanggal'       => ['required', 'date'],
            'waktu_mulai'   => ['required'],
            'waktu_selesai' => ['nullable'],
            'lokasi'        => ['nullable', 'string', 'max:255'],
            'peserta'       => ['nullable', 'string', 'max:255'],
            'deskripsi'     => ['nullable', 'string', 'max:1000'],
        ], [
            'judul.required'       => 'Judul kegiatan wajib diisi.',
            'tanggal.required'     => 'Tanggal wajib diisi.',
            'waktu_mulai.required' => 'Waktu mulai wajib diisi.',
        ]);

        $tanggal = Carbon::parse($request->tanggal);
        $status  = $tanggal->isFuture() ? 'Akan Datang'
                 : ($tanggal->isToday() ? 'Berlangsung' : 'Selesai');

        Agenda::create([
            'judul'         => $request->judul,
            'tanggal'       => $request->tanggal,
            'waktu_mulai'   => $request->waktu_mulai,
            'waktu_selesai' => $request->waktu_selesai,
            'lokasi'        => $request->lokasi,
            'peserta'       => $request->peserta,
            'deskripsi'     => $request->deskripsi,
            'status'        => $status,
            'dibuat_oleh'   => 'admin',
            'admin_id'      => Auth::guard('admin')->id(),
            'user_id'       => null,
        ]);

        ActivityLog::catat(
            'Menambahkan agenda: ' . $request->judul,
            'Buku Agenda',
            'admin'
        );

        return redirect()->route('buku_agenda')
            ->with('success', 'Agenda "' . $request->judul . '" berhasil ditambahkan.');
    }

    /**
     * Update agenda
     */
    public function update(Request $request, $id)
    {
        $agenda = Agenda::findOrFail($id);

        $request->validate([
            'judul'         => ['required', 'string', 'max:255'],
            'tanggal'       => ['required', 'date'],
            'waktu_mulai'   => ['required'],
            'waktu_selesai' => ['nullable'],
            'lokasi'        => ['nullable', 'string', 'max:255'],
            'peserta'       => ['nullable', 'string', 'max:255'],
            'deskripsi'     => ['nullable', 'string', 'max:1000'],
        ], [
            'judul.required'       => 'Judul kegiatan wajib diisi.',
            'tanggal.required'     => 'Tanggal wajib diisi.',
            'waktu_mulai.required' => 'Waktu mulai wajib diisi.',
        ]);

        $tanggal = Carbon::parse($request->tanggal);
        $status  = $tanggal->isFuture() ? 'Akan Datang'
                 : ($tanggal->isToday() ? 'Berlangsung' : 'Selesai');

        $agenda->judul         = $request->judul;
        $agenda->tanggal       = $request->tanggal;
        $agenda->waktu_mulai   = $request->waktu_mulai;
        $agenda->waktu_selesai = $request->waktu_selesai;
        $agenda->lokasi        = $request->lokasi;
        $agenda->peserta       = $request->peserta;
        $agenda->deskripsi     = $request->deskripsi;
        $agenda->status        = $status;
        $agenda->save();

        ActivityLog::catat(
            'Memperbarui agenda: ' . $agenda->judul,
            'Buku Agenda',
            'admin'
        );

        return redirect()->route('buku_agenda')
            ->with('success', 'Agenda berhasil diperbarui.');
    }

    /**
     * Hapus agenda
     */
    public function destroy($id)
    {
        $agenda = Agenda::findOrFail($id);

        ActivityLog::catat(
            'Menghapus agenda: ' . $agenda->judul,
            'Buku Agenda',
            'admin'
        );

        $agenda->delete();

        return redirect()->route('buku_agenda')
            ->with('success', 'Agenda berhasil dihapus.');
    }
}