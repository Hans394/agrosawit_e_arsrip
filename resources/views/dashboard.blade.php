@extends('layouts.admin')

@section('title', 'Beranda')

@section('content')
<div class="container mt-3 pt-5 pb-5">

    <p class="h6">Dashboard</p>

    {{-- Alert Sukses --}}
    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
        <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- ─── KARTU STATISTIK ─────────────────────────────────────── -->
    <div class="row g-4">

        <!-- Total Arsip -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm" style="background-color: #D8E9A8;">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Total Arsip Tersedia</h6>
                        <h4 class="fw-bold mb-1">{{ number_format($totalArsip) }}</h4>
                        <small class="{{ $persenArsip >= 0 ? 'text-success' : 'text-danger' }}">
                            <i class="bi bi-arrow-{{ $persenArsip >= 0 ? 'up' : 'down' }}"></i>
                            {{ abs($persenArsip) }}% bulan ini
                        </small>
                    </div>
                    <div class="d-flex justify-content-center align-items-center rounded"
                        style="width:55px; height:55px; background-color:#66bb6a;">
                        <i class="bi bi-file-earmark-text text-white fs-4"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Arsip Terbaru -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm" style="background-color: #D8E9A8;">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Arsip Terbaru</h6>
                        <h4 class="fw-bold mb-1">{{ $arsipTerbaru }}</h4>
                        <small class="text-muted">7 hari terakhir</small>
                    </div>
                    <div class="d-flex justify-content-center align-items-center rounded"
                        style="width:55px; height:55px; background-color:#66bb6a;">
                        <i class="bi bi-folder2 text-white fs-4"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kategori Arsip -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm" style="background-color: #D8E9A8;">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Kategori Arsip</h6>
                        <h4 class="fw-bold mb-1">{{ $totalKategori }}</h4>
                        <small class="text-muted">Kategori tersedia</small>
                    </div>
                    <div class="d-flex justify-content-center align-items-center rounded"
                        style="width:55px; height:55px; background-color:#66bb6a;">
                        <i class="bi bi-tags text-white fs-4"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Aktivitas Hari Ini -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm" style="background-color: #D8E9A8;">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Aktivitas Hari Ini</h6>
                        <h4 class="fw-bold mb-1">{{ $aktivitasHariIni }}</h4>
                        <small class="text-muted">Total kegiatan hari ini</small>
                    </div>
                    <div class="d-flex justify-content-center align-items-center rounded"
                        style="width:55px; height:55px; background-color:#66bb6a;">
                        <i class="bi bi-activity text-white fs-4"></i>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- ─── ARSIP TERBARU ───────────────────────────────────────── -->
    <div class="card mt-4 border-0 shadow-sm" style="background-color: #D8E9A8;">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0">Arsip Terbaru</h5>
                <a href="{{ route('data_arsip') }}" class="text-decoration-none text-success">
                    Lihat Semua <i class="bi bi-arrow-right"></i>
                </a>
            </div>

            @if ($arsipTerbaruList->isEmpty())
            <p class="text-muted text-center py-3">Belum ada arsip.</p>
            @else
            <ul class="list-group list-group-flush">
                @foreach ($arsipTerbaruList as $arsip)
                <li class="list-group-item d-flex justify-content-between align-items-center"
                    style="background-color: #D8E9A8; border-bottom: 1px solid #b5cc8e;">
                    <div class="d-flex align-items-center gap-3">
                        <div class="d-flex justify-content-center align-items-center rounded"
                            style="width:38px; height:38px; background-color:#66bb6a; min-width:38px;">
                            <i class="bi bi-file-earmark-text text-white"></i>
                        </div>
                        <div>
                            <div class="fw-semibold">{{ $arsip->judul_dokumen }}</div>
                            <small class="text-muted">
                                {{ $arsip->kategori }} &nbsp;·&nbsp;
                                {{ $arsip->tanggal_arsip->format('d/m/Y') }}
                            </small>
                        </div>
                    </div>
                    <span class="badge rounded-pill" style="background-color:#4E9F3D;">
                        {{ $arsip->nomor_arsip }}
                    </span>
                </li>
                @endforeach
            </ul>
            @endif
        </div>
    </div>

    <!-- ─── AKTIVITAS TERAKHIR ─────────────────────────────────── -->
    <div class="card mt-4 border-0 shadow-sm" style="background-color: #D8E9A8;">
        <div class="card-body">
            <h5 class="fw-bold mb-3">Aktivitas Terakhir</h5>

            @if ($aktivitasTerakhir->isEmpty())
            <p class="text-muted text-center py-3">Belum ada aktivitas tercatat.</p>
            @else
            <ul class="list-group list-group-flush">
                @foreach ($aktivitasTerakhir as $log)
                <li class="list-group-item d-flex justify-content-between align-items-center"
                    style="background-color: #D8E9A8; border-bottom: 1px solid #b5cc8e;">
                    <div class="d-flex align-items-center gap-2">
                        {{-- Icon sesuai modul --}}
                        @if (str_contains($log->modul, 'Autentikasi'))
                        <i class="bi bi-box-arrow-in-right text-success fs-5"></i>
                        @elseif (str_contains($log->modul, 'Profil'))
                        <i class="bi bi-person-gear text-primary fs-5"></i>
                        @elseif (str_contains($log->modul, 'Arsip'))
                        <i class="bi bi-file-earmark-text text-warning fs-5"></i>
                        @elseif (str_contains($log->modul, 'Agenda'))
                        <i class="bi bi-book text-info fs-5"></i>
                        @else
                        <i class="bi bi-activity text-secondary fs-5"></i>
                        @endif
                        <div>
                            <div>{{ $log->aktivitas }}</div>
                            <small class="text-muted">
                                {{ $log->created_at->diffForHumans() }}
                                &nbsp;·&nbsp; {{ $log->modul }}
                            </small>
                        </div>
                    </div>
                    <span class="badge bg-light text-dark ms-2">
                        IP: {{ $log->ip_address }}
                    </span>
                </li>
                @endforeach
            </ul>
            @endif
        </div>
    </div>

</div>
@endsection