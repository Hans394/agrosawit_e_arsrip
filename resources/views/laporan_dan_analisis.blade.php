@extends('layouts.admin')

@section('title', 'Laporan & Analisis')

@section('content')
<div class="container mt-5 pt-5">

    {{-- Alert Sukses --}}
    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
        <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

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

    <!-- TEMPLATE LAPORAN -->
    <div class="card border-0 rounded-4 mb-5" style="background-color:#D8E9A8;">
        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h6 class="fw-bold mb-0">Template Laporan</h6>
                    <small class="text-muted">Generate laporan sesuai kebutuhan Anda</small>
                </div>
                <button class="btn text-white rounded-3 px-4" style="background-color: #4E9F3D;"
                    data-bs-toggle="modal" data-bs-target="#uploadTemplateModal">
                    <i class="bi bi-upload"></i> Upload Template
                </button>
            </div>

            @if ($templateLaporan->isEmpty())
            <div class="text-center py-4">
                <i class="bi bi-file-earmark-x" style="font-size:40px; color:#4E9F3D;"></i>
                <p class="mt-2 text-muted">Belum ada template laporan. Upload sekarang!</p>
            </div>
            @else
            <div class="row g-3">
                @foreach ($templateLaporan as $template)
                <div class="col-md-6">
                    <div class="card border-0 rounded-4">
                        <div class="card-body d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="fw-bold mb-1">
                                    <i class="bi bi-file-earmark-text"></i>
                                    {{ $template->nama_template }}
                                </h6>
                                <small class="text-muted d-block">
                                    {{ $template->deskripsi ?? '-' }}
                                </small>
                                <span class="badge bg-light text-dark mt-2">
                                    {{ $template->tanggal_upload->format('d M Y') }}
                                </span>
                                <span class="badge bg-light text-muted mt-2 ms-1">
                                    <i class="bi bi-person"></i>
                                    {{ $template->admin->name ?? '-' }}
                                </span>
                            </div>
                            <div class="d-flex flex-column gap-2 align-items-end">
                                @if ($template->file_path)
                                <a href="{{ Storage::url($template->file_path) }}"
                                    download="{{ $template->file_name }}"
                                    class="btn btn-success btn-sm">
                                    <i class="bi bi-download"></i> Download
                                </a>
                                @endif
                                <button class="btn btn-danger btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#hapusTemplate{{ $template->id }}">
                                    <i class="bi bi-trash"></i> Hapus
                                </button>
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

{{-- MODAL HAPUS --}}
@foreach ($templateLaporan as $template)
<div class="modal fade" id="hapusTemplate{{ $template->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4">
            <div class="modal-body text-center p-4">
                <i class="bi bi-exclamation-triangle-fill text-danger" style="font-size:40px;"></i>
                <h5 class="fw-bold mt-3">Hapus Template</h5>
                <p class="text-muted">Apakah Anda yakin ingin menghapus</p>
                <strong>"{{ $template->nama_template }}"</strong>?
                <div class="d-flex justify-content-center gap-3 mt-3">
                    <button class="btn btn-light px-4" data-bs-dismiss="modal">Batal</button>
                    <form action="{{ route('laporan.destroy', $template->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger px-4">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach

<!-- MODAL UPLOAD TEMPLATE -->
<div class="modal fade" id="uploadTemplateModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 rounded-5 overflow-hidden">

            <div class="modal-header border-0" style="background-color:#D8E9A8;">
                <h4 class="fw-bold mb-0">Upload Template Laporan</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body px-4 pb-4" style="background-color:#D8E9A8;">

                {{-- Alert Error --}}
                @if ($errors->any())
                <div class="alert alert-danger rounded-3">
                    <i class="bi bi-exclamation-triangle-fill me-1"></i>
                    {{ $errors->first() }}
                </div>
                @endif

                <form action="{{ route('laporan.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- NAMA TEMPLATE -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Nama Template</label>
                        <input type="text" name="nama_template" value="{{ old('nama_template') }}"
                            class="form-control rounded-pill py-2 @error('nama_template') is-invalid @enderror"
                            placeholder="Masukkan nama template">
                        @error('nama_template')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <!-- TANGGAL -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Tanggal Upload</label>
                        <input type="date" name="tanggal_upload" value="{{ old('tanggal_upload', now()->format('Y-m-d')) }}"
                            class="form-control rounded-pill py-2 @error('tanggal_upload') is-invalid @enderror">
                        @error('tanggal_upload')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <!-- FILE -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Upload File</label>
                        <label for="uploadFile"
                            class="w-100 border rounded-4 bg-light text-center py-5"
                            style="cursor:pointer;">
                            <i class="bi bi-upload fs-1 d-block text-dark"></i>
                            <span class="fw-bold text-primary fs-5" id="labelFile">Klik Untuk Upload</span>
                            <div class="text-muted">PDF, DOC, XLS, atau gambar (Max. 10MB)</div>
                        </label>
                        <input type="file" name="file" id="uploadFile" class="d-none"
                            accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png"
                            onchange="document.getElementById('labelFile').textContent = this.files[0].name">
                        @error('file')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    <!-- DESKRIPSI -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Deskripsi</label>
                        <textarea name="deskripsi" rows="4" class="form-control rounded-4"
                            placeholder="Masukkan deskripsi template...">{{ old('deskripsi') }}</textarea>
                    </div>

                    <!-- BUTTON -->
                    <div class="d-flex gap-3">
                        <button type="submit" class="btn text-white px-5 py-2 rounded-4 fw-bold"
                            style="background-color:#4E9F3D;">
                            <i class="bi bi-floppy"></i> Simpan
                        </button>
                        <button type="button" class="btn px-5 py-2 rounded-4 fw-bold"
                            style="background-color:#e0e0e0;" data-bs-dismiss="modal">
                            Batal
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

@endsection