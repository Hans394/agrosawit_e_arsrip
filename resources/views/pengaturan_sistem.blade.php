@extends('layouts.admin')

@section('title', 'Pengaturan Sistem')

@section('content')
<div class="container mt-5 mb-5 pt-5 pb-5">

    {{-- Alert Sukses --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
            <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- HEADER -->
    <div class="card border-0 rounded-4 mb-4" style="background:#D8E9A8;">
        <div class="card-body d-flex align-items-center gap-3">
            <i class="bi bi-gear fs-3"></i>
            <div>
                <h6 class="fw-bold mb-0">Pengaturan Sistem</h6>
                <small class="text-muted">Konfigurasi dan preferensi sistem pengarsipan</small>
            </div>
        </div>
    </div>

    <form action="{{ route('pengaturan_sistem.update') }}" method="POST">
        @csrf
        @method('PUT')

        <!-- PENGATURAN UMUM -->
        <div class="card border-0 rounded-4 mb-4" style="background:#D8E9A8;">
            <div class="card-body">
                <h6 class="fw-bold mb-3"><i class="bi bi-gear"></i> Pengaturan Umum</h6>

                <div class="mb-3">
                    <label class="form-label">Nama Sistem</label>
                    <input type="text" name="nama_sistem"
                        value="{{ old('nama_sistem', $pengaturan->nama_sistem) }}"
                        class="form-control rounded-pill @error('nama_sistem') is-invalid @enderror">
                    @error('nama_sistem')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Maksimal Ukuran File Upload (MB)</label>
                    <select name="max_upload_mb" class="form-select rounded-pill">
                        <option value="5"  {{ $pengaturan->max_upload_mb == 5  ? 'selected' : '' }}>5 MB</option>
                        <option value="10" {{ $pengaturan->max_upload_mb == 10 ? 'selected' : '' }}>10 MB</option>
                        <option value="20" {{ $pengaturan->max_upload_mb == 20 ? 'selected' : '' }}>20 MB</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Session Timeout (menit)</label>
                    <select name="session_timeout" class="form-select rounded-pill">
                        <option value="15" {{ $pengaturan->session_timeout == 15 ? 'selected' : '' }}>15 Menit</option>
                        <option value="30" {{ $pengaturan->session_timeout == 30 ? 'selected' : '' }}>30 Menit</option>
                        <option value="60" {{ $pengaturan->session_timeout == 60 ? 'selected' : '' }}>60 Menit</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- DATABASE & BACKUP -->
        <div class="card border-0 rounded-4 mb-4" style="background:#D8E9A8;">
            <div class="card-body">
                <h6 class="fw-bold mb-3"><i class="bi bi-database"></i> Database & Backup</h6>

                <div class="d-flex justify-content-between align-items-center mb-3 p-2 rounded-3 bg-light">
                    <div>
                        <strong>Auto Backup Database</strong><br>
                        <small>Backup otomatis database secara berkala</small>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox"
                            name="auto_backup" value="1"
                            {{ $pengaturan->auto_backup ? 'checked' : '' }}>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Frekuensi Backup</label>
                    <select name="frekuensi_backup" class="form-select rounded-pill">
                        <option value="Harian"   {{ $pengaturan->frekuensi_backup == 'Harian'   ? 'selected' : '' }}>Harian</option>
                        <option value="Mingguan" {{ $pengaturan->frekuensi_backup == 'Mingguan' ? 'selected' : '' }}>Mingguan</option>
                        <option value="Bulanan"  {{ $pengaturan->frekuensi_backup == 'Bulanan'  ? 'selected' : '' }}>Bulanan</option>
                    </select>
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('pengaturan_sistem.backup') }}"
                       class="btn text-white" style="background:#66bb6a;">
                        <i class="bi bi-download"></i> Backup Sekarang
                    </a>
                </div>
            </div>
        </div>

        <!-- NOTIFIKASI -->
        <div class="card border-0 rounded-4 mb-4" style="background:#D8E9A8;">
            <div class="card-body">
                <h6 class="fw-bold mb-3"><i class="bi bi-bell"></i> Notifikasi</h6>

                <div class="d-flex justify-content-between align-items-center mb-3 p-2 rounded-3 bg-light">
                    <div>
                        <strong>Email Notification</strong><br>
                        <small>Terima notifikasi melalui email</small>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox"
                            name="email_notifikasi" value="1"
                            {{ $pengaturan->email_notifikasi ? 'checked' : '' }}>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center p-2 rounded-3 bg-light">
                    <div>
                        <strong>System Notification</strong><br>
                        <small>Notifikasi dalam sistem</small>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox"
                            name="system_notifikasi" value="1"
                            {{ $pengaturan->system_notifikasi ? 'checked' : '' }}>
                    </div>
                </div>
            </div>
        </div>

        <!-- KEAMANAN -->
        <div class="card border-0 rounded-4 mb-4" style="background:#D8E9A8;">
            <div class="card-body">
                <h6 class="fw-bold mb-3"><i class="bi bi-shield-lock"></i> Keamanan</h6>

                <div class="d-flex justify-content-between align-items-center p-2 rounded-3 bg-light mb-3">
                    <div>
                        <strong>Two-Factor Authentication</strong><br>
                        <small>Aktifkan autentikasi dua faktor</small>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox"
                            name="two_factor_auth" value="1"
                            {{ $pengaturan->two_factor_auth ? 'checked' : '' }}>
                    </div>
                </div>

                @if (!$pengaturan->two_factor_auth)
                    <div class="alert alert-warning d-flex align-items-center">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <small>
                            Perhatian: Aktifkan Two-Factor Authentication untuk keamanan akun yang lebih baik.
                        </small>
                    </div>
                @endif
            </div>
        </div>

        <!-- TAMPILAN -->
        <div class="card border-0 rounded-4 mb-4" style="background:#D8E9A8;">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Tampilan</h6>
                <label class="form-label">Theme</label>
                <select name="theme" class="form-select rounded-pill">
                    <option value="Light" {{ $pengaturan->theme == 'Light' ? 'selected' : '' }}>Light</option>
                    <option value="Dark"  {{ $pengaturan->theme == 'Dark'  ? 'selected' : '' }}>Dark</option>
                </select>
            </div>
        </div>

        <!-- BUTTON -->
        <div class="d-flex gap-2">
            <button type="submit" class="btn text-white" style="background:#66bb6a;">
                <i class="bi bi-floppy"></i> Simpan Pengaturan
            </button>
            <a href="{{ route('pengaturan_sistem.reset') }}"
               class="btn btn-light border"
               onclick="return confirm('Reset semua pengaturan ke nilai default?')">
                <i class="bi bi-arrow-repeat"></i> Reset ke Default
            </a>
        </div>

    </form>

</div>
@endsection