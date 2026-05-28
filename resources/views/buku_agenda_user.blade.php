@extends('layouts.user')

@section('title', 'Buku Agenda User')

@section('content')
<div class="container mt-4 pt-4 pb-5">

    {{-- Alert Sukses --}}
    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
        <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="fw-bold mb-1">Buku Agenda</h5>
            <small class="text-muted">Daftar agenda dan kegiatan perusahaan</small>
        </div>
    </div>

    <!-- STATISTIK -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 rounded-4" style="background-color: #D8E9A8">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="text-white p-3 rounded-3" style="background-color: #4E9F3D">
                        <i class="bi bi-calendar-event fs-5"></i>
                    </div>
                    <div>
                        <h5 class="mb-0 fw-bold">{{ $agendaMendatang }}</h5>
                        <small>Agenda Mendatang</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 rounded-4" style="background-color: #D8E9A8">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="text-white p-3 rounded-3" style="background-color: #4E9F3D">
                        <i class="bi bi-clock-history fs-5"></i>
                    </div>
                    <div>
                        <h5 class="mb-0 fw-bold">{{ $agendaSelesai }}</h5>
                        <small>Agenda Selesai</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 rounded-4" style="background-color: #D8E9A8">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="text-white p-3 rounded-3" style="background-color: #4E9F3D">
                        <i class="bi bi-people fs-5"></i>
                    </div>
                    <div>
                        <h5 class="mb-0 fw-bold">{{ $totalAgenda }}</h5>
                        <small>Total Agenda</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- DAFTAR AGENDA -->
    @forelse ($agenda as $item)
    <div class="card border-0 rounded-4 mb-3" style="background-color: #D8E9A8">
        <div class="card-body d-flex align-items-start">

            <!-- TANGGAL -->
            <div class="text-center me-4" style="min-width: 65px;">
                <div class="text-white p-2 rounded-3" style="background-color: #4E9F3D">
                    <small>{{ $item->tanggal->format('M') }}</small>
                    <div class="fw-bold fs-5">{{ $item->tanggal->format('d') }}</div>
                    <small>{{ $item->tanggal->format('Y') }}</small>
                </div>
            </div>

            <!-- ISI -->
            <div class="flex-grow-1">
                <h6 class="fw-semibold mb-1">{{ $item->judul }}</h6>
                <p class="small text-muted mb-2">{{ $item->deskripsi ?? '-' }}</p>

                <div class="d-flex flex-wrap gap-3 small">
                    <span>
                        <i class="bi bi-clock"></i>
                        {{ $item->waktu_mulai }}
                        @if ($item->waktu_selesai) - {{ $item->waktu_selesai }} @endif
                    </span>
                    @if ($item->lokasi)
                    <span><i class="bi bi-geo-alt"></i> {{ $item->lokasi }}</span>
                    @endif
                    @if ($item->peserta)
                    <span><i class="bi bi-people"></i> {{ $item->peserta }}</span>
                    @endif
                </div>

                <!-- Dibuat oleh -->
                <div class="mt-2">
                    @if ($item->dibuat_oleh == 'admin')
                    <span class="badge rounded-pill" style="background-color:#4E9F3D; font-size:10px;">
                        <i class="bi bi-shield-check"></i> Admin
                    </span>
                    @else
                    <span class="badge rounded-pill bg-primary" style="font-size:10px;">
                        <i class="bi bi-person"></i> {{ $item->user->name ?? 'User' }}
                    </span>
                    @endif
                </div>
            </div>

            <!-- STATUS + HAPUS -->
            <div class="d-flex flex-column align-items-end gap-2">
                {{-- Badge Status --}}
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

    @empty
    <div class="text-center py-5">
        <i class="bi bi-calendar-x" style="font-size:50px; color:#4E9F3D;"></i>
        <p class="mt-3 text-muted">Belum ada agenda.</p>
    </div>
    @endforelse

</div>

@endsection