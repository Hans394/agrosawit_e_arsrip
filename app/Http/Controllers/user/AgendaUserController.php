<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Agenda;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AgendaUserController extends Controller
{
    /**
     * Tampilkan semua agenda
     */
    public function index()
    {
        $userId = Auth::guard('web')->id();

        // Ambil semua agenda (dari admin dan user)
        $agenda = Agenda::with(['user', 'admin'])
            ->orderBy('tanggal', 'asc')
            ->get();

        // Update status otomatis berdasarkan tanggal
        foreach ($agenda as $item) {
            $item->updateStatus();
        }

        // Statistik
        $agendaMendatang = Agenda::where('status', 'Akan Datang')->count();
        $agendaSelesai   = Agenda::where('status', 'Selesai')->count();
        $totalAgenda     = Agenda::count();

        // Catat aktivitas
        ActivityLog::catat('Melihat buku agenda', 'Buku Agenda', 'web');

        return view('buku_agenda_user', compact(
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
            'judul'          => ['required', 'string', 'max:255'],
            'tanggal'        => ['required', 'date'],
            'waktu_mulai'    => ['required'],
            'waktu_selesai'  => ['nullable'],
            'lokasi'         => ['nullable', 'string', 'max:255'],
            'deskripsi'      => ['nullable', 'string', 'max:1000'],
            'peserta'        => ['nullable', 'string', 'max:255'],
        ], [
            'judul.required'       => 'Judul kegiatan wajib diisi.',
            'tanggal.required'     => 'Tanggal wajib diisi.',
            'waktu_mulai.required' => 'Waktu mulai wajib diisi.',
        ]);

        // Tentukan status berdasarkan tanggal
        $tanggal = Carbon::parse($request->tanggal);
        if ($tanggal->isFuture()) {
            $status = 'Akan Datang';
        } elseif ($tanggal->isToday()) {
            $status = 'Berlangsung';
        } else {
            $status = 'Selesai';
        }

        Agenda::create([
            'judul'         => $request->judul,
            'tanggal'       => $request->tanggal,
            'waktu_mulai'   => $request->waktu_mulai,
            'waktu_selesai' => $request->waktu_selesai,
            'lokasi'        => $request->lokasi,
            'deskripsi'     => $request->deskripsi,
            'peserta'       => $request->peserta,
            'status'        => $status,
            'dibuat_oleh'   => 'user',
            'user_id'       => Auth::guard('web')->id(),
            'admin_id'      => null,
        ]);

        ActivityLog::catat(
            'Menambahkan agenda: ' . $request->judul,
            'Buku Agenda',
            'web'
        );

        return redirect()->route('buku_agenda_user')
            ->with('success', 'Agenda "' . $request->judul . '" berhasil ditambahkan.');
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
            'web'
        );

        $agenda->delete();

        return redirect()->route('buku_agenda_user')
            ->with('success', 'Agenda berhasil dihapus.');
    }
}