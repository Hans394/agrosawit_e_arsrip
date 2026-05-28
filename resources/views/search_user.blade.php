@extends('layouts.user')

@section('title', 'Hasil Pencarian')
@section('page_title', 'Hasil Pencarian')

@section('content')
<div class="container mt-5 pt-4 pb-5">

    <!-- HEADER PENCARIAN -->
    <div class="card border-0 rounded-4 mb-4" style="background-color:#D8E9A8;">
        <div class="card-body d-flex justify-content-between align-items-center">
            <div>
                <h6 class="fw-bold mb-0">
                    <i class="bi bi-search me-2"></i>
                    Hasil pencarian untuk: <span style="color:#4E9F3D;">"{{ $keyword }}"</span>
                </h6>
                <small class="text-muted">Ditemukan {{ $totalHasil }} hasil dari semua kategori</small>
            </div>
            <a href="{{ route('dashboard_user') }}" class="btn btn-sm btn-light border rounded-pill px-3">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    {{-- Tidak ada hasil --}}
    @if ($totalHasil == 0)
        <div class="text-center py-5">
            <i class="bi bi-search" style="font-size:50px; color:#ccc;"></i>
            <h6 class="mt-3 text-muted">Tidak ada hasil untuk "{{ $keyword }}"</h6>
            <p class="text-muted small">Coba kata kunci yang berbeda</p>
        </div>
    @endif

    {{-- ─── HASIL ARSIP ──────────────────────────────────────── --}}
    @if ($arsip->count() > 0)
    <div class="mb-4">
        <div class="d-flex align-items-center gap-2 mb-3">
            <i class="bi bi-file-earmark-text fs-5" style="color:#4E9F3D;"></i>
            <h6 class="fw-bold mb-0">Arsip <span class="badge rounded-pill" style="background-color:#4E9F3D;">{{ $arsip->count() }}</span></h6>
        </div>

        @foreach ($arsip as $item)
        <div class="card border-0 rounded-4 mb-2 shadow-sm">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="fw-semibold">{{ $item->judul_dokumen }}</div>
                    <small class="text-muted">
                        {{ $item->nomor_arsip }} &nbsp;·&nbsp;
                        {{ $item->kategori }} &nbsp;·&nbsp;
                        {{ $item->tanggal_arsip->format('d/m/Y') }}
                    </small>
                    @if ($item->keterangan)
                        <p class="small text-muted mb-0 mt-1">{{ Str::limit($item->keterangan, 80) }}</p>
                    @endif
                </div>
                <div class="d-flex gap-2 ms-3">
                    @if ($item->file_path)
                        <a href="{{ Storage::url($item->file_path) }}"
                           download="{{ $item->file_name }}"
                           class="btn btn-sm text-white rounded-pill px-3"
                           style="background-color:#4E9F3D;">
                            <i class="bi bi-download"></i>
                        </a>
                    @endif
                    <a href="{{ route('data_arsip_user') }}?cari={{ $item->nomor_arsip }}"
                       class="btn btn-sm btn-light border rounded-pill px-3">
                        <i class="bi bi-eye"></i>
                    </a>
                </div>
            </div>
        </div>
        @endforeach

        <a href="{{ route('data_arsip_user') }}?cari={{ $keyword }}"
           class="btn btn-sm btn-light border rounded-pill px-3 mt-1">
            Lihat semua arsip <i class="bi bi-arrow-right"></i>
        </a>
    </div>
    @endif

    {{-- ─── HASIL AGENDA ─────────────────────────────────────── --}}
    @if ($agenda->count() > 0)
    <div class="mb-4">
        <div class="d-flex align-items-center gap-2 mb-3">
            <i class="bi bi-calendar-event fs-5" style="color:#4E9F3D;"></i>
            <h6 class="fw-bold mb-0">Agenda <span class="badge rounded-pill" style="background-color:#4E9F3D;">{{ $agenda->count() }}</span></h6>
        </div>

        @foreach ($agenda as $item)
        <div class="card border-0 rounded-4 mb-2 shadow-sm">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div class="d-flex gap-3 align-items-center">
                    <div class="text-center text-white p-2 rounded-3 flex-shrink-0"
                        style="background:#66bb6a; min-width:55px;">
                        <small>{{ $item->tanggal->format('M') }}</small>
                        <div class="fw-bold">{{ $item->tanggal->format('d') }}</div>
                        <small>{{ $item->tanggal->format('Y') }}</small>
                    </div>
                    <div>
                        <div class="fw-semibold">{{ $item->judul }}</div>
                        <small class="text-muted">
                            <i class="bi bi-clock"></i> {{ $item->waktu_mulai }}
                            @if ($item->waktu_selesai) - {{ $item->waktu_selesai }} @endif
                            @if ($item->lokasi)
                                &nbsp;·&nbsp; <i class="bi bi-geo-alt"></i> {{ $item->lokasi }}
                            @endif
                        </small>
                        @if ($item->deskripsi)
                            <p class="small text-muted mb-0 mt-1">{{ Str::limit($item->deskripsi, 80) }}</p>
                        @endif
                    </div>
                </div>
                <div>
                    @if ($item->status == 'Akan Datang')
                        <span class="badge bg-warning text-dark">Akan Datang</span>
                    @elseif ($item->status == 'Berlangsung')
                        <span class="badge bg-primary">Berlangsung</span>
                    @else
                        <span class="badge bg-secondary">Selesai</span>
                    @endif
                </div>
            </div>
        </div>
        @endforeach

        <a href="{{ route('buku_agenda_user') }}"
           class="btn btn-sm btn-light border rounded-pill px-3 mt-1">
            Lihat semua agenda <i class="bi bi-arrow-right"></i>
        </a>
    </div>
    @endif

    {{-- ─── HASIL LAPORAN ────────────────────────────────────── --}}
    @if ($laporan->count() > 0)
    <div class="mb-4">
        <div class="d-flex align-items-center gap-2 mb-3">
            <i class="bi bi-bar-chart fs-5" style="color:#4E9F3D;"></i>
            <h6 class="fw-bold mb-0">Template Laporan <span class="badge rounded-pill" style="background-color:#4E9F3D;">{{ $laporan->count() }}</span></h6>
        </div>

        @foreach ($laporan as $item)
        <div class="card border-0 rounded-4 mb-2 shadow-sm">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="fw-semibold">
                        <i class="bi bi-file-earmark-text text-success"></i>
                        {{ $item->nama_template }}
                    </div>
                    <small class="text-muted">
                        {{ $item->tanggal_upload->format('d M Y') }}
                        @if ($item->admin)
                            &nbsp;·&nbsp; <i class="bi bi-shield-check"></i> {{ $item->admin->name }}
                        @endif
                    </small>
                    @if ($item->deskripsi)
                        <p class="small text-muted mb-0 mt-1">{{ Str::limit($item->deskripsi, 80) }}</p>
                    @endif
                </div>
                @if ($item->file_path)
                    <a href="{{ Storage::url($item->file_path) }}"
                       download="{{ $item->file_name }}"
                       class="btn btn-sm btn-success rounded-pill px-3 ms-3">
                        <i class="bi bi-download"></i> Download
                    </a>
                @endif
            </div>
        </div>
        @endforeach

        <a href="{{ route('laporan_dan_analisis_user') }}"
           class="btn btn-sm btn-light border rounded-pill px-3 mt-1">
            Lihat semua laporan <i class="bi bi-arrow-right"></i>
        </a>
    </div>
    @endif

</div>
@endsection