@extends('layouts.admin')

@section('title', 'Hasil Pencarian Admin')

@section('content')
<div class="container mt-5 pt-5 pb-5">

    <!-- HEADER PENCARIAN -->
    <div class="card border-0 rounded-4 mb-4 shadow-sm" style="background-color:#D8E9A8;">
        <div class="card-body d-flex justify-content-between align-items-center">
            <div>
                <h6 class="fw-bold mb-0 text-success-emphasis">
                    <i class="bi bi-search me-2"></i>
                    Hasil pencarian admin untuk: <span style="color:#4E9F3D;">"{{ $keyword }}"</span>
                </h6>
                <small class="text-muted">Ditemukan {{ $totalHasil }} hasil dari semua kategori</small>
            </div>
            <a href="{{ route('dashboard') }}" class="btn btn-sm btn-light border rounded-pill px-3">
                <i class="bi bi-arrow-left"></i> Kembali ke Beranda
            </a>
        </div>
    </div>

    {{-- Tidak ada hasil --}}
    @if ($totalHasil == 0)
        <div class="text-center py-5">
            <i class="bi bi-search text-muted" style="font-size:50px;"></i>
            <h6 class="mt-3 text-muted">Tidak ada hasil untuk "{{ $keyword }}"</h6>
            <p class="text-muted small">Coba gunakan kata kunci lainnya</p>
        </div>
    @endif

    {{-- ─── HASIL ARSIP ──────────────────────────────────────── --}}
    @if ($arsip->count() > 0)
    <div class="mb-4">
        <div class="d-flex align-items-center gap-2 mb-3">
            <i class="bi bi-file-earmark-text fs-5" style="color:#4E9F3D;"></i>
            <h5 class="fw-bold mb-0">Arsip <span class="badge rounded-pill" style="background-color:#4E9F3D;">{{ $arsip->count() }}</span></h5>
        </div>

        @foreach ($arsip as $item)
        <div class="card border-0 rounded-4 mb-2 shadow-sm" style="background-color: #fcfdfe; border-left: 4px solid #4E9F3D !important;">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="fw-semibold text-dark">{{ $item->judul_dokumen }}</div>
                    <small class="text-muted">
                        {{ $item->nomor_arsip }} &nbsp;·&nbsp;
                        <strong>{{ $item->kategori }}</strong> &nbsp;·&nbsp;
                        {{ $item->tanggal_arsip->format('d/m/Y') }}
                    </small>
                    @if ($item->keterangan)
                        <p class="small text-muted mb-0 mt-1">{{ Str::limit($item->keterangan, 120) }}</p>
                    @endif
                </div>
                <div class="d-flex gap-2 ms-3">
                    @if ($item->file_path)
                        <a href="{{ Storage::url($item->file_path) }}"
                           download="{{ $item->file_name }}"
                           class="btn btn-sm text-white rounded-pill px-3"
                           style="background-color:#4E9F3D;"
                           title="Download File">
                            <i class="bi bi-download"></i>
                        </a>
                    @endif
                    <a href="{{ route('data_arsip') }}?cari={{ $item->nomor_arsip }}"
                       class="btn btn-sm btn-light border rounded-pill px-3"
                       title="Kelola Arsip">
                        <i class="bi bi-pencil-square text-success"></i> Kelola
                    </a>
                </div>
            </div>
        </div>
        @endforeach

        <a href="{{ route('data_arsip') }}?cari={{ $keyword }}"
           class="btn btn-sm btn-light border rounded-pill px-3 mt-1">
            Lihat di data arsip <i class="bi bi-arrow-right"></i>
        </a>
    </div>
    @endif

    {{-- ─── HASIL AGENDA ─────────────────────────────────────── --}}
    @if ($agenda->count() > 0)
    <div class="mb-4">
        <div class="d-flex align-items-center gap-2 mb-3">
            <i class="bi bi-calendar-event fs-5" style="color:#4E9F3D;"></i>
            <h5 class="fw-bold mb-0">Agenda <span class="badge rounded-pill" style="background-color:#4E9F3D;">{{ $agenda->count() }}</span></h5>
        </div>

        @foreach ($agenda as $item)
        <div class="card border-0 rounded-4 mb-2 shadow-sm" style="background-color: #fcfdfe; border-left: 4px solid #0d6efd !important;">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div class="d-flex gap-3 align-items-center">
                    <div class="text-center text-white p-2 rounded-3 flex-shrink-0"
                        style="background:#66bb6a; min-width:55px;">
                        <small>{{ $item->tanggal->format('M') }}</small>
                        <div class="fw-bold fs-5">{{ $item->tanggal->format('d') }}</div>
                        <small>{{ $item->tanggal->format('Y') }}</small>
                    </div>
                    <div>
                        <div class="fw-semibold text-dark">{{ $item->judul }}</div>
                        <small class="text-muted">
                            <i class="bi bi-clock"></i> {{ $item->waktu_mulai }}
                            @if ($item->waktu_selesai) - {{ $item->waktu_selesai }} @endif
                            @if ($item->lokasi)
                                &nbsp;·&nbsp; <i class="bi bi-geo-alt"></i> {{ $item->lokasi }}
                            @endif
                        </small>
                        @if ($item->deskripsi)
                            <p class="small text-muted mb-0 mt-1">{{ Str::limit($item->deskripsi, 120) }}</p>
                        @endif
                    </div>
                </div>
                <div class="d-flex gap-2 align-items-center ms-3">
                    @if ($item->status == 'Akan Datang')
                        <span class="badge bg-warning text-dark me-2">Akan Datang</span>
                    @elseif ($item->status == 'Berlangsung')
                        <span class="badge bg-primary me-2">Berlangsung</span>
                    @else
                        <span class="badge bg-secondary me-2">Selesai</span>
                    @endif
                    <a href="{{ route('buku_agenda') }}" class="btn btn-sm btn-light border rounded-pill px-3">
                        <i class="bi bi-pencil-square text-success"></i> Kelola
                    </a>
                </div>
            </div>
        </div>
        @endforeach

        <a href="{{ route('buku_agenda') }}"
           class="btn btn-sm btn-light border rounded-pill px-3 mt-1">
            Buka buku agenda <i class="bi bi-arrow-right"></i>
        </a>
    </div>
    @endif

    {{-- ─── HASIL LAPORAN ────────────────────────────────────── --}}
    @if ($laporan->count() > 0)
    <div class="mb-4">
        <div class="d-flex align-items-center gap-2 mb-3">
            <i class="bi bi-bar-chart fs-5" style="color:#4E9F3D;"></i>
            <h5 class="fw-bold mb-0">Template Laporan <span class="badge rounded-pill" style="background-color:#4E9F3D;">{{ $laporan->count() }}</span></h5>
        </div>

        @foreach ($laporan as $item)
        <div class="card border-0 rounded-4 mb-2 shadow-sm" style="background-color: #fcfdfe; border-left: 4px solid #ffc107 !important;">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="fw-semibold text-dark">
                        <i class="bi bi-file-earmark-text text-success me-1"></i>
                        {{ $item->nama_template }}
                    </div>
                    <small class="text-muted">
                        Diunggah pada: {{ $item->tanggal_upload->format('d M Y') }}
                        @if ($item->admin)
                            &nbsp;·&nbsp; <i class="bi bi-shield-check"></i> Pengunggah: {{ $item->admin->name }}
                        @endif
                    </small>
                    @if ($item->deskripsi)
                        <p class="small text-muted mb-0 mt-1">{{ Str::limit($item->deskripsi, 120) }}</p>
                    @endif
                </div>
                <div class="d-flex gap-2 ms-3">
                    @if ($item->file_path)
                        <a href="{{ Storage::url($item->file_path) }}"
                           download="{{ $item->file_name }}"
                           class="btn btn-sm btn-success rounded-pill px-3">
                            <i class="bi bi-download"></i> Download
                        </a>
                    @endif
                    <a href="{{ route('laporan_dan_analisis') }}" class="btn btn-sm btn-light border rounded-pill px-3">
                        <i class="bi bi-pencil-square text-success"></i> Kelola
                    </a>
                </div>
            </div>
        </div>
        @endforeach

        <a href="{{ route('laporan_dan_analisis') }}"
           class="btn btn-sm btn-light border rounded-pill px-3 mt-1">
            Lihat semua laporan <i class="bi bi-arrow-right"></i>
        </a>
    </div>
    @endif

</div>
@endsection
