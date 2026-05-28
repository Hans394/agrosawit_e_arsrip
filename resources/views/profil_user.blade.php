@extends('layouts.user')

@section('title', 'Profil User')

@section('content')
<div class="container mt-5 mb-5">

    {{-- Alert Sukses --}}
    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
        <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- CARD PROFIL HEADER -->
    <div class="card border-0 rounded-4 mb-4 p-4" style="background-color:#4E9F3D; color:white;">
        <div class="d-flex align-items-center gap-3">

            <!-- FOTO -->
            <img src="{{ asset('images/logo.png') }}" width="50" height="50"
                class="rounded-circle bg-light p-1">

            <!-- INFO -->
            <div>
                <h6 class="fw-bold mb-0">{{ $user->name }}</h6>
                <small class="d-block">{{ $user->jabatan ?? 'User' }}</small>
                <div class="d-flex gap-3 mt-2 flex-wrap">
                    <small><i class="bi bi-envelope"></i> {{ $user->email }}</small>
                    <small><i class="bi bi-telephone"></i> {{ $user->phone ?? '-' }}</small>
                </div>
            </div>

        </div>
    </div>

    <!-- FORM PROFIL -->
    <div class="card border-0 rounded-4 p-4" style="background-color:#D8E9A8;">
        <h6 class="fw-bold mb-3">Informasi Profil</h6>

        <div class="row">

            <!-- NAMA -->
            <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">
                    <i class="bi bi-person"></i> Nama Lengkap
                </label>
                <input type="text" class="form-control rounded-pill"
                    value="{{ $user->name }}" readonly>
            </div>

            <!-- EMAIL -->
            <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">
                    <i class="bi bi-envelope"></i> Email
                </label>
                <input type="email" class="form-control rounded-pill"
                    value="{{ $user->email }}" readonly>
            </div>

            <!-- TELEPON -->
            <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">
                    <i class="bi bi-telephone"></i> Nomor Telepon
                </label>
                <input type="text" class="form-control rounded-pill"
                    value="{{ $user->phone ?? '-' }}" readonly>
            </div>

            <!-- JABATAN -->
            <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">
                    <i class="bi bi-briefcase"></i> Jabatan
                </label>
                <input type="text" class="form-control rounded-pill"
                    value="{{ $user->jabatan ?? '-' }}" readonly>
            </div>

            <!-- DIVISI -->
            <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">
                    Divisi/Unit Kerja
                </label>
                <input type="text" class="form-control rounded-pill"
                    value="{{ $user->divisi ?? '-' }}" readonly>
            </div>

            <!-- ALAMAT -->
            <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">
                    <i class="bi bi-geo-alt"></i> Alamat
                </label>
                <input type="text" class="form-control rounded-pill"
                    value="{{ $user->alamat ?? '-' }}" readonly>
            </div>

        </div>

        <!-- CATATAN -->
        <div class="mt-3 p-3 rounded-3 text-center" style="background-color:#c3cfd1; font-size:14px;">
            <i style="color: blue">
                Catatan: Untuk mengubah informasi pribadi, silakan hubungi Admin HRD atau IT Support.
            </i>
        </div>

    </div>

</div>
@endsection