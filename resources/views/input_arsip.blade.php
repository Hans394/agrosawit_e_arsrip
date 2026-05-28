@extends('layouts.admin')

@section('title', 'Input Arsip')

@section('content')
<div class="container mt-5 pt-5 mb-5">

    <!-- Card Form -->
    <div class="p-4 rounded-4" style="background-color:#dbe8b4; max-width:800px; margin:auto;">

        <h5 class="fw-bold">Form Input Arsip Digital</h5>
        <small class="text-muted">Lengkapi informasi dokumen arsip yang akan disimpan</small>

        {{-- Alert Sukses --}}
        @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 mt-3" role="alert">
            <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        {{-- Alert Error --}}
        @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show rounded-3 mt-3" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-1"></i> {{ $errors->first() }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        {{-- Form POST + @csrf + enctype untuk upload file --}}
        <form action="{{ route('arsip.store') }}" method="POST" enctype="multipart/form-data" class="mt-4">
            @csrf

            <!-- Nomor & Tanggal -->
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nomor Arsip <span class="text-danger">*</span></label>
                    <input type="text"
                        name="nomor_arsip"
                        value="{{ old('nomor_arsip') }}"
                        class="form-control rounded-pill @error('nomor_arsip') is-invalid @enderror"
                        placeholder="Contoh: ARS/2026/001"
                        required>
                    @error('nomor_arsip')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Tanggal Arsip <span class="text-danger">*</span></label>
                    <input type="date"
                        name="tanggal_arsip"
                        value="{{ old('tanggal_arsip') }}"
                        class="form-control rounded-pill @error('tanggal_arsip') is-invalid @enderror"
                        required>
                    @error('tanggal_arsip')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Judul -->
            <div class="mt-3">
                <label class="form-label">Judul Dokumen <span class="text-danger">*</span></label>
                <input type="text"
                    name="judul_dokumen"
                    value="{{ old('judul_dokumen') }}"
                    class="form-control rounded-pill @error('judul_dokumen') is-invalid @enderror"
                    placeholder="Masukkan judul dokumen"
                    required>
                @error('judul_dokumen')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Kategori -->
            <div class="mt-3">
                <label class="form-label">Kategori <span class="text-danger">*</span></label>
                <select name="kategori"
                    class="form-select rounded-pill @error('kategori') is-invalid @enderror"
                    required>
                    <option value="" selected disabled>Pilih kategori</option>
                    <option value="SK (Surat Keputusan)" {{ old('kategori') == 'SK (Surat Keputusan)' ? 'selected' : '' }}>SK (Surat Keputusan)</option>
                    <option value="Laporan" {{ old('kategori') == 'Laporan' ? 'selected' : '' }}>Laporan</option>
                    <option value="Kontrak" {{ old('kategori') == 'Kontrak' ? 'selected' : '' }}>Kontrak</option>
                    <option value="Memo" {{ old('kategori') == 'Memo' ? 'selected' : '' }}>Memo</option>
                    <option value="Notulen" {{ old('kategori') == 'Notulen' ? 'selected' : '' }}>Notulen</option>
                </select>
                @error('kategori')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Upload File -->
            <div class="mt-3">
                <label class="form-label">Upload File <span class="text-danger">*</span></label>

                <div class="border border-2 border-secondary-subtle rounded-4 p-4 text-center bg-light">
                    <input type="file"
                        name="file"
                        class="form-control d-none @error('file') is-invalid @enderror"
                        id="fileInput"
                        accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png"
                        onchange="tampilNamaFile(this)">

                    <label for="fileInput" class="w-100" style="cursor:pointer;">
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="currentColor"
                                class="bi bi-upload" viewBox="0 0 16 16">
                                <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5" />
                                <path d="M7.646 1.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 2.707V11.5a.5.5 0 0 1-1 0V2.707L5.354 4.854a.5.5 0 1 1-.708-.708z" />
                            </svg>
                            <p class="mb-1 text-primary fw-semibold" id="labelFile">Klik Untuk Upload</p>
                            <small class="text-muted">PDF, DOC, XLS, atau gambar (Max. 10MB)</small>
                        </div>
                    </label>
                </div>
                @error('file')
                <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Keterangan -->
            <div class="mt-3">
                <label class="form-label">Keterangan</label>
                <textarea name="keterangan"
                    class="form-control rounded-4 @error('keterangan') is-invalid @enderror"
                    rows="3"
                    placeholder="Tambahkan catatan atau keterangan tambahan...">{{ old('keterangan') }}</textarea>
                @error('keterangan')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Button -->
            <div class="mt-4 d-flex gap-3">
                <button type="submit" class="btn rounded-pill px-4 text-white"
                    style="background-color: #4E9F3D;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="white"
                        class="bi bi-floppy" viewBox="0 0 16 16">
                        <path d="M11 2H9v3h2z" />
                        <path d="M1.5 0h11.586a1.5 1.5 0 0 1 1.06.44l1.415 1.414A1.5 1.5 0 0 1 16 2.914V14.5a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 14.5v-13A1.5 1.5 0 0 1 1.5 0M1 1.5v13a.5.5 0 0 0 .5.5H2v-4.5A1.5 1.5 0 0 1 3.5 9h9a1.5 1.5 0 0 1 1.5 1.5V15h.5a.5.5 0 0 0 .5-.5V2.914a.5.5 0 0 0-.146-.353l-1.415-1.415A.5.5 0 0 0 13.086 1H13v4.5A1.5 1.5 0 0 1 11.5 7h-7A1.5 1.5 0 0 1 3 5.5V1H1.5a.5.5 0 0 0-.5.5m3 4a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5V1H4zM3 15h10v-4.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5z" />
                    </svg>
                    Simpan Arsip
                </button>

                <button type="reset" class="btn rounded-pill px-4 text-white"
                    style="background-color: #888;"
                    onclick="document.getElementById('labelFile').innerText='Klik Untuk Upload'">
                    Reset Form
                </button>
            </div>

        </form>
    </div>
</div>

<script>
    // Tampilkan nama file yang dipilih
    function tampilNamaFile(input) {
        const label = document.getElementById('labelFile');
        if (input.files && input.files[0]) {
            label.textContent = input.files[0].name;
        } else {
            label.textContent = 'Klik Untuk Upload';
        }
    }
</script>

@endsection