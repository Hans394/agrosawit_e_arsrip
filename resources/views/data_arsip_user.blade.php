@extends('layouts.user')

@section('title', 'Data Arsip')

@section('content')
<div class="container mt-5 pt-5 mb-5">

    {{-- Alert Sukses --}}
    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
        <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- CARD TOTAL -->
    <div class="d-flex justify-content-center mb-4">
        <div class="card border-0 p-4" style="background-color: #c3d69b; border-radius: 30px; width: 420px;">
            <div class="d-flex align-items-center">
                <div class="d-flex justify-content-center align-items-center me-3"
                    style="width: 70px; height: 70px; background-color: #4CAF50; border-radius: 20px;">
                    <i class="bi bi-file-earmark-text text-white fs-3"></i>
                </div>
                <div>
                    <h4 class="fw-bold mb-1">{{ $totalArsip }}</h4>
                    <p class="mb-0 fs-5">Total Arsip Tersedia</p>
                </div>
            </div>
        </div>
    </div>

    <!-- FILTER -->
    <div class="card border-0 rounded-4 mb-4" style="background:#D8E9A8;">
        <div class="card-body">
            <form action="{{ route('data_arsip_user') }}" method="GET">

                <label class="form-label">Cari Nama Arsip atau Nomor</label>
                <input type="text" name="cari" value="{{ request('cari') }}"
                    class="form-control mb-3 rounded-pill"
                    placeholder="Masukkan Nama Dokumen atau Nomor Arsip...">

                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Kategori</label>
                        <select name="kategori" class="form-select rounded-pill">
                            <option value="Semua Kategori">Semua Kategori</option>
                            <option value="SK (Surat Keputusan)" {{ request('kategori') == 'SK (Surat Keputusan)' ? 'selected' : '' }}>SK (Surat Keputusan)</option>
                            <option value="Laporan" {{ request('kategori') == 'Laporan'  ? 'selected' : '' }}>Laporan</option>
                            <option value="Kontrak" {{ request('kategori') == 'Kontrak'  ? 'selected' : '' }}>Kontrak</option>
                            <option value="Memo" {{ request('kategori') == 'Memo'     ? 'selected' : '' }}>Memo</option>
                            <option value="Notulen" {{ request('kategori') == 'Notulen'  ? 'selected' : '' }}>Notulen</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}"
                            class="form-control rounded-pill">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Tanggal Akhir</label>
                        <input type="date" name="tanggal_akhir" value="{{ request('tanggal_akhir') }}"
                            class="form-control rounded-pill">
                    </div>
                </div>

                <div class="mt-3 d-flex gap-2">
                    <button type="submit" class="btn text-white rounded-pill px-3" style="background-color: #4E9F3D;">
                        <i class="bi bi-search"></i> Cari Arsip
                    </button>
                    <a href="{{ route('data_arsip_user') }}" class="btn btn-light border rounded-pill px-3">
                        <i class="bi bi-funnel"></i> Reset Filter
                    </a>
                </div>

            </form>
        </div>
    </div>

    <!-- TABEL -->
    <table class="table align-middle mb-0" style="border-collapse: collapse;">
        <thead>
            <tr>
                <th class="ps-4 py-3 text-white" style="background-color: #4E9F3D;">Nomor Arsip</th>
                <th class="py-3 text-white" style="background-color: #4E9F3D;">Nama Dokumen</th>
                <th class="py-3 text-white" style="background-color: #4E9F3D;">Kategori</th>
                <th class="py-3 text-white" style="background-color: #4E9F3D;">Tanggal</th>
                <th class="py-3 text-white" style="background-color: #4E9F3D;">Diinput Oleh</th>
                <th class="py-3 text-white text-center pe-4" style="background-color: #4E9F3D;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($arsip as $item)
            <tr style="border-bottom: 2px solid #000;">
                <td class="ps-4 py-3" style="background-color: #D8E9A8;">{{ $item->nomor_arsip }}</td>
                <td style="background-color: #D8E9A8;">{{ $item->judul_dokumen }}</td>
                <td style="background-color: #D8E9A8;">{{ $item->kategori }}</td>
                <td style="background-color: #D8E9A8;">{{ $item->tanggal_arsip->format('d/m/Y') }}</td>
                <td style="background-color: #D8E9A8;">
                    @if ($item->admin)
                    <span class="badge rounded-pill" style="background-color:#4E9F3D;">
                        <i class="bi bi-shield-check"></i> Admin
                    </span>
                    @elseif ($item->user)
                    <span class="badge rounded-pill bg-primary">
                        <i class="bi bi-person"></i> {{ $item->user->name }}
                    </span>
                    @else
                    <span class="text-muted">-</span>
                    @endif
                </td>
                <td class="text-center pe-4" style="background-color: #D8E9A8;">

                    {{-- Lihat Detail --}}
                    <button class="btn btn-sm p-1"
                        data-bs-toggle="modal"
                        data-bs-target="#detailArsip{{ $item->id }}"
                        title="Lihat Detail">
                        <i class="bi bi-eye fs-5"></i>
                    </button>

                    {{-- Download --}}
                    @if ($item->file_path)
                    <a href="{{ Storage::url($item->file_path) }}"
                        download="{{ $item->file_name }}"
                        class="btn btn-sm p-1" title="Unduh File">
                        <i class="bi bi-download fs-5"></i>
                    </a>
                    @else
                    <span class="text-muted p-1"><i class="bi bi-download fs-5"></i></span>
                    @endif

                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center py-5" style="background-color: #D8E9A8;">
                    <i class="bi bi-inbox" style="font-size:40px; color:#4E9F3D;"></i>
                    <p class="mt-2 mb-0 text-muted">Belum ada data arsip.</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- PAGINATION -->
    <div class="d-flex justify-content-between align-items-center px-4 py-3"
        style="background-color: #D8E9A8; border-top: 2px solid #000;">

        <small>Menampilkan {{ $arsip->firstItem() ?? 0 }}-{{ $arsip->lastItem() ?? 0 }}
            dari {{ $arsip->total() }} arsip</small>

        <div class="d-flex align-items-center gap-2">

            @if ($arsip->onFirstPage())
            <button class="btn btn-light border rounded-pill btn-sm px-3" disabled>Sebelumnya</button>
            @else
            <a href="{{ $arsip->previousPageUrl() }}"
                class="btn btn-light border rounded-pill btn-sm px-3 text-dark text-decoration-none">
                Sebelumnya
            </a>
            @endif

            @for ($i = 1; $i <= $arsip->lastPage(); $i++)
                @if ($i == $arsip->currentPage())
                <span class="btn btn-sm rounded-circle text-white fw-bold d-flex align-items-center justify-content-center"
                    style="background-color: #4E9F3D; width: 36px; height: 36px;">{{ $i }}</span>
                @else
                <a href="{{ $arsip->url($i) }}"
                    class="btn btn-light border rounded-circle btn-sm text-dark text-decoration-none d-flex align-items-center justify-content-center"
                    style="width: 36px; height: 36px;">{{ $i }}</a>
                @endif
                @endfor

                @if ($arsip->hasMorePages())
                <a href="{{ $arsip->nextPageUrl() }}"
                    class="btn btn-light border rounded-pill btn-sm px-3 text-dark text-decoration-none">
                    Selanjutnya
                </a>
                @else
                <button class="btn btn-light border rounded-pill btn-sm px-3" disabled>Selanjutnya</button>
                @endif

        </div>
    </div>

</div>

{{-- MODALS DETAIL --}}
@foreach ($arsip as $item)
<div class="modal fade" id="detailArsip{{ $item->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header text-white" style="background-color:#4E9F3D;">
                <h5 class="modal-title">Detail Arsip</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="p-3 rounded" style="background-color:#dcecc5;">
                    <h6 class="fw-bold">{{ $item->judul_dokumen }}</h6>
                    <small class="text-muted">
                        <i class="bi bi-calendar"></i> {{ $item->tanggal_arsip->format('d F Y') }}
                        &nbsp;|&nbsp;
                        <i class="bi bi-tag"></i> {{ $item->kategori }}
                    </small>
                </div>

                <div class="mt-3">
                    <label class="fw-bold">Nomor Arsip</label>
                    <p class="text-muted mb-2">{{ $item->nomor_arsip }}</p>
                </div>

                <div class="mt-2">
                    <label class="fw-bold">Keterangan</label>
                    <p class="text-muted mb-2">{{ $item->keterangan ?? '-' }}</p>
                </div>

                <div class="mt-2">
                    <label class="fw-bold">Diinput Oleh</label>
                    <p class="mb-2">
                        @if ($item->admin)
                        <span class="badge rounded-pill" style="background-color:#4E9F3D;">Admin</span>
                        {{ $item->admin->name }}
                        @elseif ($item->user)
                        <span class="badge rounded-pill bg-primary">User</span>
                        {{ $item->user->name }}
                        @else
                        -
                        @endif
                    </p>
                </div>

                <div class="mt-3">
                    <label class="fw-bold">Preview Dokumen</label>
                    <div class="border rounded text-center p-4" style="background-color:#eaf5d7;">
                        @if ($item->file_path)
                        <i class="bi bi-file-earmark-check text-success" style="font-size:40px;"></i>
                        <p class="mt-2 mb-0 small">{{ $item->file_name }}</p>
                        @else
                        <i class="bi bi-file-earmark-x text-muted" style="font-size:40px;"></i>
                        <p class="mt-2 mb-0 small text-muted">Tidak ada file</p>
                        @endif
                    </div>
                </div>

                <small class="text-muted d-block mt-2">ID Arsip: {{ $item->id }}</small>
            </div>
            <div class="modal-footer">
                @if ($item->file_path)
                <a href="{{ Storage::url($item->file_path) }}"
                    download="{{ $item->file_name }}"
                    class="btn btn-sm text-white" style="background-color:#4E9F3D;">
                    <i class="bi bi-download"></i> Unduh File
                </a>
                @endif
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endforeach

@endsection