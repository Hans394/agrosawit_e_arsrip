@extends('layouts.admin')

@section('title', 'Kelola Profil')

@section('content')

<div class="container mt-5 pt-5 pb-5">

    {{-- Alert Sukses --}}
    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
        <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Profile Header -->
    <div class="rounded-4 p-4 d-flex justify-content-between align-items-center mb-4"
        style="background-color: #4E9F3D;">
        <div class="d-flex align-items-center gap-3">
            <img src="{{ asset('images/logo.png') }}" width="50" height="50"
                class="rounded-circle bg-light p-1">
            <div style="color: white;">
                <h5 class="mb-0">{{ $admin->name }}</h5>
                <small>{{ $admin->jabatan ?? 'Administrator Sistem' }}</small><br>
                <small><i class="bi bi-envelope"></i> {{ $admin->email }}</small>
            </div>
        </div>
        <div style="color: white;">
            <i class="bi bi-telephone"></i> {{ $admin->phone ?? '-' }}
        </div>
    </div>

    <!-- TABS -->
    <ul class="nav nav-tabs mb-3" id="profilTab">
        <li class="nav-item">
            <a class="nav-link {{ session('tab') == 'password' || session('tab') == 'tambah-user' ? '' : 'active' }}"
                href="#profil" data-bs-toggle="tab">Informasi Profil</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ session('tab') == 'password' ? 'active' : '' }}"
                href="#password" data-bs-toggle="tab">Ubah Password</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ session('tab') == 'tambah-user' ? 'active' : '' }}"
                href="#tambah-user" data-bs-toggle="tab">Tambah Akun User</a>
        </li>
    </ul>

    <div class="tab-content">

        {{-- TAB 1: INFORMASI PROFIL --}}
        <div class="tab-pane fade {{ session('tab') == 'password' || session('tab') == 'tambah-user' ? '' : 'show active' }}"
            id="profil">
            <div class="card border-0 rounded-4 p-4" style="background-color: #D8E9A8;">
                <h6 class="fw-bold mb-3">Informasi Profil</h6>
                <form action="{{ route('admin.profil.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label"><i class="bi bi-person"></i> Nama Lengkap</label>
                            <input type="text" name="name" value="{{ old('name', $admin->name) }}"
                                class="form-control @error('name') is-invalid @enderror">
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><i class="bi bi-envelope"></i> Email</label>
                            <input type="email" name="email" value="{{ old('email', $admin->email) }}"
                                class="form-control @error('email') is-invalid @enderror">
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><i class="bi bi-telephone"></i> Nomor Telepon</label>
                            <input type="text" name="phone" value="{{ old('phone', $admin->phone) }}"
                                class="form-control" placeholder="+62 812-XXXX-XXXX">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><i class="bi bi-suitcase-lg"></i> Jabatan</label>
                            <input type="text" name="jabatan" value="{{ old('jabatan', $admin->jabatan) }}"
                                class="form-control" placeholder="Administrator Sistem">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Divisi/Unit Kerja</label>
                            <input type="text" name="divisi" value="{{ old('divisi', $admin->divisi) }}"
                                class="form-control" placeholder="IT & Pengarsipan">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><i class="bi bi-geo-alt"></i> Alamat</label>
                            <input type="text" name="alamat" value="{{ old('alamat', $admin->alamat) }}"
                                class="form-control" placeholder="Jambi, Indonesia">
                        </div>
                    </div>
                    <div class="mt-3">
                        <label class="form-label">Bio/Deskripsi</label>
                        <textarea name="bio" class="form-control" rows="3"
                            placeholder="Tuliskan deskripsi singkat...">{{ old('bio', $admin->bio) }}</textarea>
                    </div>
                    <button type="submit" class="btn mt-3 rounded-pill px-4"
                        style="background-color: #4E9F3D; color: white;">
                        <i class="bi bi-floppy"></i> Simpan Data
                    </button>
                </form>
            </div>
        </div>

        {{-- TAB 2: UBAH PASSWORD --}}
        <div class="tab-pane fade {{ session('tab') == 'password' ? 'show active' : '' }}" id="password">
            <div class="card border-0 rounded-4 p-4" style="background-color: #D8E9A8;">
                <h6 class="fw-bold mb-3">Ubah Password</h6>
                <form action="{{ route('admin.profil.password') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Password Saat Ini</label>
                        <input type="password" name="current_password"
                            class="form-control @error('current_password') is-invalid @enderror"
                            placeholder="Masukkan password saat ini">
                        @error('current_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Password Baru</label>
                            <input type="password" name="password"
                                class="form-control @error('password') is-invalid @enderror"
                                placeholder="Minimal 8 karakter">
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Konfirmasi Password Baru</label>
                            <input type="password" name="password_confirmation"
                                class="form-control" placeholder="Ulangi password baru">
                        </div>
                    </div>
                    <button type="submit" class="btn mt-3 rounded-pill px-4"
                        style="background-color: #4E9F3D; color: white;">
                        <i class="bi bi-floppy"></i> Ubah Password
                    </button>
                </form>
            </div>
        </div>

        {{-- TAB 3: TAMBAH AKUN USER --}}
        <div class="tab-pane fade {{ session('tab') == 'tambah-user' ? 'show active' : '' }}" id="tambah-user">
            <div class="card border-0 rounded-4 p-4" style="background-color: #D8E9A8;">
                <h6 class="fw-bold mb-3">Tambah Akun User</h6>
                <form action="{{ route('admin.profil.tambah-user') }}" method="POST">
                    @csrf
                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label"><i class="bi bi-person"></i> Nama Lengkap</label>
                            <input type="text" name="name" value="{{ old('name') }}"
                                class="form-control rounded-pill @error('name') is-invalid @enderror"
                                placeholder="Nama lengkap user">
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label"><i class="bi bi-envelope"></i> Email</label>
                            <input type="email" name="email" value="{{ old('email') }}"
                                class="form-control rounded-pill @error('email') is-invalid @enderror"
                                placeholder="user@example.com">
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- ✅ Nomor Telepon --}}
                        <div class="col-md-6">
                            <label class="form-label"><i class="bi bi-telephone"></i> Nomor Telepon</label>
                            <input type="text" name="phone" value="{{ old('phone') }}"
                                class="form-control rounded-pill @error('phone') is-invalid @enderror"
                                placeholder="+62 812-XXXX-XXXX">
                            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- ✅ Jabatan --}}
                        <div class="col-md-6">
                            <label class="form-label"><i class="bi bi-suitcase-lg"></i> Jabatan</label>
                            <input type="text" name="jabatan" value="{{ old('jabatan') }}"
                                class="form-control rounded-pill @error('jabatan') is-invalid @enderror"
                                placeholder="Contoh: Staff IT">
                            @error('jabatan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- ✅ Divisi --}}
                        <div class="col-md-6">
                            <label class="form-label"><i class="bi bi-building"></i> Divisi/Unit Kerja</label>
                            <input type="text" name="divisi" value="{{ old('divisi') }}"
                                class="form-control rounded-pill @error('divisi') is-invalid @enderror"
                                placeholder="Contoh: IT & Pengarsipan">
                            @error('divisi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- ✅ Alamat --}}
                        <div class="col-md-6">
                            <label class="form-label"><i class="bi bi-geo-alt"></i> Alamat</label>
                            <input type="text" name="alamat" value="{{ old('alamat') }}"
                                class="form-control rounded-pill @error('alamat') is-invalid @enderror"
                                placeholder="Contoh: Jambi, Indonesia">
                            @error('alamat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label"><i class="bi bi-lock"></i> Password</label>
                            <input type="password" name="password"
                                class="form-control rounded-pill @error('password') is-invalid @enderror"
                                placeholder="Minimal 8 karakter">
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label"><i class="bi bi-lock-fill"></i> Konfirmasi Password</label>
                            <input type="password" name="password_confirmation"
                                class="form-control rounded-pill"
                                placeholder="Ulangi password">
                        </div>

                    </div>
                    <button type="submit" class="btn mt-3 rounded-pill px-4"
                        style="background-color: #4E9F3D; color: white;">
                        <i class="bi bi-person-plus"></i> Buat Akun User
                    </button>
                </form>
            </div>
        </div>

    </div>

    {{-- ✅ AKTIVITAS TERAKHIR — Data dari database --}}
    <div class="card mt-4 mb-4 border-0 rounded-4" style="background-color: #D8E9A8;">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0">Aktivitas Terakhir</h6>
                <small class="text-muted">10 aktivitas terbaru</small>
            </div>

            @if ($aktivitas->isEmpty())
            <p class="text-muted text-center py-3">Belum ada aktivitas tercatat.</p>
            @else
            <ul class="list-group list-group-flush">
                @foreach ($aktivitas as $log)
                <li class="list-group-item d-flex justify-content-between align-items-start py-2"
                    style="background-color: #D8E9A8; border-bottom: 1px solid #b5cc8e;">

                    <div class="d-flex align-items-center gap-2">
                        {{-- Icon sesuai modul --}}
                        @if (str_contains($log->modul, 'Autentikasi'))
                        <i class="bi bi-box-arrow-in-right text-success"></i>
                        @elseif (str_contains($log->modul, 'Profil'))
                        <i class="bi bi-person-gear text-primary"></i>
                        @elseif (str_contains($log->modul, 'Arsip'))
                        <i class="bi bi-file-earmark-text text-warning"></i>
                        @elseif (str_contains($log->modul, 'Agenda'))
                        <i class="bi bi-book text-info"></i>
                        @else
                        <i class="bi bi-activity text-secondary"></i>
                        @endif

                        <div>
                            <div>{{ $log->aktivitas }}</div>
                            <small class="text-muted">
                                {{ $log->created_at->diffForHumans() }}
                                &nbsp;·&nbsp; Modul: {{ $log->modul }}
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