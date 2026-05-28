@extends('layouts.user')

@section('title', 'Laporan & Analisis')

@section('content')
<div class="container mt-5 pt-5 pb-5">

    <!-- HEADER -->
    <div class="card border-0 rounded-4 mb-4" style="background-color:#D8E9A8;">
        <div class="card-body">
            <h5 class="fw-bold mb-1">Laporan dan Analisis Arsip</h5>
            <small class="text-muted">Insight dan analisis data pengarsipan digital</small>
        </div>
    </div>

    <!-- STATISTIK -->
    <div class="row g-3 mb-4">

        <div class="col-md-4">
            <div class="card border-0 text-white rounded-4" style="background-color:#4e8fae;">
                <div class="card-body d-flex justify-content-between">
                    <div>
                        <small>Total Arsip {{ now()->year }}</small>
                        <h4 class="fw-bold">{{ number_format($totalArsipTahunIni) }}</h4>
                        <small>
                            {{ $persenKenaikan >= 0 ? '↑' : '↓' }}
                            {{ abs($persenKenaikan) }}% dari tahun lalu
                        </small>
                    </div>
                    <i class="bi bi-file-earmark-text fs-2"></i>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 text-white rounded-4" style="background-color:#5aa04e;">
                <div class="card-body d-flex justify-content-between">
                    <div>
                        <small>Rata-rata Per Bulan</small>
                        <h4 class="fw-bold">{{ number_format($rataRataPerBulan) }}</h4>
                        <small>Arsip yang diproses per bulan</small>
                    </div>
                    <i class="bi bi-calendar fs-2"></i>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 text-white rounded-4" style="background-color:#b043b0;">
                <div class="card-body d-flex justify-content-between">
                    <div>
                        <small>Kelengkapan File</small>
                        <h4 class="fw-bold">{{ $efisiensi }}%</h4>
                        <small>Arsip dengan file terlampir</small>
                    </div>
                    <i class="bi bi-graph-up-arrow fs-2"></i>
                </div>
            </div>
        </div>

    </div>

    <!-- TEMPLATE LAPORAN — User hanya bisa lihat & download -->
    <div class="card border-0 rounded-4 mb-5" style="background-color:#D8E9A8;">
        <div class="card-body">

            <div class="mb-3">
                <h6 class="fw-bold mb-0">Template Laporan</h6>
                <small class="text-muted">Download template laporan yang tersedia</small>
            </div>

            @if ($templateLaporan->isEmpty())
            <div class="text-center py-4">
                <i class="bi bi-file-earmark-x" style="font-size:40px; color:#4E9F3D;"></i>
                <p class="mt-2 text-muted">Belum ada template laporan tersedia.</p>
            </div>
            @else
            <div class="row g-3">
                @foreach ($templateLaporan as $template)
                <div class="col-md-6">
                    <div class="card border-0 rounded-4 shadow-sm">
                        <div class="card-body d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="fw-bold mb-1">
                                    <i class="bi bi-file-earmark-text text-success"></i>
                                    {{ $template->nama_template }}
                                </h6>
                                <small class="text-muted d-block">
                                    {{ $template->deskripsi ?? '-' }}
                                </small>
                                <span class="badge bg-light text-dark mt-2">
                                    {{ $template->tanggal_upload->format('d M Y') }}
                                </span>
                                <span class="badge bg-light text-muted mt-2 ms-1">
                                    <i class="bi bi-shield-check"></i>
                                    {{ $template->admin->name ?? 'Admin' }}
                                </span>
                            </div>

                            {{-- User hanya bisa download, tidak bisa hapus --}}
                            <div class="align-self-end">
                                @if ($template->file_path)
                                <a href="{{ Storage::url($template->file_path) }}"
                                    download="{{ $template->file_name }}"
                                    class="btn btn-success btn-sm">
                                    <i class="bi bi-download"></i> Download
                                </a>
                                @else
                                <span class="btn btn-secondary btn-sm disabled">
                                    <i class="bi bi-file-earmark-x"></i> Tidak ada file
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif

        </div>
    </div>

</div>
@endsection