@extends('layouts.user')

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
                    <div class="text-white p-3 rounded-3" style="background-color: #4E9F3D;">
                        <i class="bi bi-file-earmark-text fs-4"></i>
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
                    <div class="text-white p-3 rounded-3" style="background-color: #4E9F3D;">
                        <i class="bi bi-folder2 fs-4"></i>
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
                    <div class="text-white p-3 rounded-3" style="background-color: #4E9F3D;">
                        <i class="bi bi-tags fs-4"></i>
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
                        <small class="text-muted">Kegiatan Anda hari ini</small>
                    </div>
                    <div class="text-white p-3 rounded-3" style="background-color: #4E9F3D;">
                        <i class="bi bi-clock fs-4"></i>
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
                <a href="{{ route('data_arsip_user') }}" class="text-decoration-none text-success">
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
                                &nbsp;·&nbsp;
                                @if ($arsip->admin)
                                <span class="badge rounded-pill" style="background-color:#4E9F3D; font-size:10px;">Admin</span>
                                @elseif ($arsip->user)
                                <span class="badge rounded-pill bg-primary" style="font-size:10px;">{{ $arsip->user->name }}</span>
                                @endif
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
</div>
@endsection