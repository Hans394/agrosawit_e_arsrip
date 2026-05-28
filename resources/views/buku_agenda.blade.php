@extends('layouts.admin')

@section('title', 'Buku Agenda')

@section('content')
<div class="container mt-5 pt-5 pb-5">

    {{-- Alert Sukses --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
            <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-2">
        <div>
            <h5 class="fw-bold mb-0">Buku Agenda</h5>
            <p class="text-muted mb-0">Daftar agenda dan kegiatan perusahaan</p>
        </div>
        <button class="btn text-white fw-semibold px-4 py-2 rounded-3"
            style="background-color:#4E9F3D;"
            data-bs-toggle="modal" data-bs-target="#tambahAgenda">
            <i class="bi bi-plus-lg me-1"></i> Tambah Agenda
        </button>
    </div>

    <!-- STATISTIK -->
    <div class="row g-3 mb-4 mt-1">
        <div class="col-md-4">
            <div class="card border-0 rounded-4" style="background-color:#D8E9A8;">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="p-3 rounded-3" style="background:#66bb6a;">
                        <i class="bi bi-calendar-event text-white fs-5"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-bold">{{ $agendaMendatang }}</h6>
                        <small>Agenda Mendatang</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 rounded-4" style="background-color:#D8E9A8;">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="p-3 rounded-3" style="background:#66bb6a;">
                        <i class="bi bi-clock-history text-white fs-5"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-bold">{{ $agendaSelesai }}</h6>
                        <small>Agenda Selesai</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 rounded-4" style="background-color:#D8E9A8;">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="p-3 rounded-3" style="background:#66bb6a;">
                        <i class="bi bi-people text-white fs-5"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-bold">{{ $totalAgenda }}</h6>
                        <small>Total Agenda</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- DAFTAR AGENDA -->
    @forelse ($agenda as $item)
    <div class="card border-0 rounded-4 mb-3" style="background-color:#D8E9A8;">
        <div class="card-body d-flex gap-3">

            <!-- TANGGAL -->
            <div class="text-center text-white p-3 rounded-3 flex-shrink-0"
                style="background:#66bb6a; min-width:70px;">
                <small>{{ $item->tanggal->format('M') }}</small>
                <h5 class="mb-0 fw-bold">{{ $item->tanggal->format('d') }}</h5>
                <small>{{ $item->tanggal->format('Y') }}</small>
            </div>

            <!-- CONTENT -->
            <div class="flex-grow-1">
                <div class="d-flex justify-content-between align-items-start">
                    <h6 class="fw-bold mb-1">{{ $item->judul }}</h6>

                    <!-- AKSI + STATUS -->
                    <div class="d-flex flex-column align-items-end">
                        <div class="d-flex gap-2">
                            {{-- Edit --}}
                            <button class="btn btn-sm p-1"
                                data-bs-toggle="modal"
                                data-bs-target="#editAgenda{{ $item->id }}"
                                title="Edit">
                                <i class="bi bi-pencil"></i>
                            </button>

                            {{-- Hapus --}}
                            <button class="btn btn-sm p-1 text-danger"
                                data-bs-toggle="modal"
                                data-bs-target="#hapusAgenda{{ $item->id }}"
                                title="Hapus">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>

                        {{-- Badge Status --}}
                        @if ($item->status == 'Akan Datang')
                            <span class="badge bg-warning text-dark mt-2">Akan Datang</span>
                        @elseif ($item->status == 'Berlangsung')
                            <span class="badge bg-primary mt-2">Berlangsung</span>
                        @else
                            <span class="badge bg-secondary mt-2">Selesai</span>
                        @endif
                    </div>
                </div>

                <!-- DESKRIPSI -->
                <small class="text-muted d-block mt-1">{{ $item->deskripsi ?? '-' }}</small>

                <!-- DETAIL -->
                <div class="d-flex flex-wrap gap-4 mt-2">
                    <small>
                        <i class="bi bi-clock"></i>
                        {{ $item->waktu_mulai }}
                        @if ($item->waktu_selesai) - {{ $item->waktu_selesai }} @endif
                    </small>
                    @if ($item->lokasi)
                        <small><i class="bi bi-geo-alt"></i> {{ $item->lokasi }}</small>
                    @endif
                    @if ($item->peserta)
                        <small><i class="bi bi-person"></i> {{ $item->peserta }}</small>
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
        </div>
    </div>
    @empty
    <div class="text-center py-5">
        <i class="bi bi-calendar-x" style="font-size:50px; color:#4E9F3D;"></i>
        <p class="mt-3 text-muted">Belum ada agenda. Tambahkan agenda baru!</p>
    </div>
    @endforelse

</div>

{{-- ═══════════ MODALS EDIT & HAPUS ═══════════ --}}
@foreach ($agenda as $item)

    {{-- MODAL EDIT --}}
    <div class="modal fade" id="editAgenda{{ $item->id }}" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0">
                <div class="modal-header text-white" style="background-color:#4E9F3D;">
                    <h5 class="modal-title">
                        <i class="bi bi-pencil-square me-2"></i> Edit Agenda
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('agenda_admin.update', $item->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body" style="background-color:#D8E9A8;">

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Judul Kegiatan <span class="text-danger">*</span></label>
                            <input type="text" name="judul" value="{{ $item->judul }}"
                                class="form-control rounded-pill" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Tanggal <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal"
                                    value="{{ $item->tanggal->format('Y-m-d') }}"
                                    class="form-control rounded-pill" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-semibold">Waktu Mulai <span class="text-danger">*</span></label>
                                <input type="time" name="waktu_mulai"
                                    value="{{ $item->waktu_mulai }}"
                                    class="form-control rounded-pill" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-semibold">Waktu Selesai</label>
                                <input type="time" name="waktu_selesai"
                                    value="{{ $item->waktu_selesai }}"
                                    class="form-control rounded-pill">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Lokasi</label>
                            <input type="text" name="lokasi" value="{{ $item->lokasi }}"
                                class="form-control rounded-pill" placeholder="Masukkan lokasi">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Peserta</label>
                            <input type="text" name="peserta" value="{{ $item->peserta }}"
                                class="form-control rounded-pill" placeholder="Masukkan peserta">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Deskripsi</label>
                            <textarea name="deskripsi" class="form-control rounded-4" rows="3">{{ $item->deskripsi }}</textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-0" style="background-color:#D8E9A8;">
                        <button type="submit" class="btn text-white px-4" style="background-color:#4E9F3D;">
                            Perbarui Agenda
                        </button>
                        <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL HAPUS --}}
    <div class="modal fade" id="hapusAgenda{{ $item->id }}" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-4">
                <div class="modal-body text-center p-4">
                    <i class="bi bi-exclamation-triangle-fill text-danger" style="font-size:40px;"></i>
                    <h5 class="fw-bold mt-3">Hapus Agenda</h5>
                    <p class="text-muted">Tindakan ini tidak dapat dibatalkan</p>
                    <div class="p-3 rounded mb-3" style="background:#f1f1f1;">
                        <small>Apakah Anda yakin ingin menghapus agenda</small><br>
                        <strong>"{{ $item->judul }}"</strong>?
                    </div>
                    <div class="d-flex justify-content-center gap-3">
                        <button class="btn btn-light px-4" data-bs-dismiss="modal">Batal</button>
                        <form action="{{ route('agenda_admin.destroy', $item->id) }}" method="POST">
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

{{-- MODAL TAMBAH AGENDA --}}
<div class="modal fade" id="tambahAgenda" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header text-white" style="background-color:#4E9F3D;">
                <h5 class="modal-title">
                    <i class="bi bi-plus-circle me-2"></i> Tambah Agenda
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('agenda_admin.store') }}" method="POST">
                @csrf
                <div class="modal-body" style="background-color:#D8E9A8;">

                    {{-- Alert Error --}}
                    @if ($errors->any())
                        <div class="alert alert-danger rounded-3">
                            <i class="bi bi-exclamation-triangle-fill me-1"></i>
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Judul Kegiatan <span class="text-danger">*</span></label>
                        <input type="text" name="judul" value="{{ old('judul') }}"
                            class="form-control rounded-pill @error('judul') is-invalid @enderror"
                            placeholder="Masukkan judul kegiatan" required>
                        @error('judul')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Tanggal <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal" value="{{ old('tanggal') }}"
                                class="form-control rounded-pill @error('tanggal') is-invalid @enderror" required>
                            @error('tanggal')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Waktu Mulai <span class="text-danger">*</span></label>
                            <input type="time" name="waktu_mulai" value="{{ old('waktu_mulai') }}"
                                class="form-control rounded-pill @error('waktu_mulai') is-invalid @enderror" required>
                            @error('waktu_mulai')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Waktu Selesai</label>
                            <input type="time" name="waktu_selesai" value="{{ old('waktu_selesai') }}"
                                class="form-control rounded-pill">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Lokasi</label>
                        <input type="text" name="lokasi" value="{{ old('lokasi') }}"
                            class="form-control rounded-pill" placeholder="Masukkan lokasi">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Peserta</label>
                        <input type="text" name="peserta" value="{{ old('peserta') }}"
                            class="form-control rounded-pill" placeholder="Masukkan peserta">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control rounded-4" rows="3"
                            placeholder="Masukkan deskripsi agenda">{{ old('deskripsi') }}</textarea>
                    </div>
                </div>

                <div class="modal-footer border-0" style="background-color:#D8E9A8;">
                    <button type="submit" class="btn text-white px-4" style="background-color:#4E9F3D;">
                        <i class="bi bi-check-lg"></i> Simpan Agenda
                    </button>
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection