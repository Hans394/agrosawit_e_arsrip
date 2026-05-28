<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Registrasi Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Icon -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body>
    <!-- Background -->
    <div class="d-flex align-items-center justify-content-center vh-100"
        style="background-image: url('{{ asset('images/bg.jpeg') }}'); 
                background-size: cover; 
                background-position: center; 
                background-repeat: no-repeat;">
        <!-- Overlay -->
        <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark opacity-25"></div>
        <!-- Card -->
        <div class="card border-0 shadow-lg p-4 position-relative"
            style="width: 420px; background-color:#cfe3a5; border-radius:20px;">
            <!-- Logo -->
            <div class="text-center mb-3">
                <img src="{{ asset('images/logo.png') }}" width="50" height="50"
                    class="rounded-circle bg-light p-1">
                <h6 class="mt-2 fw-semibold">Registrasi Admin</h6>
            </div>

            {{-- Alert Error --}}
            @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show rounded-3" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-1"></i>
                {{ $errors->first() }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            {{-- Alert Sukses --}}
            @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
                <i class="bi bi-check-circle-fill me-1"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            <!-- Form — METHOD POST + @csrf -->
            <form action="{{ route('admin.register.post') }}" method="POST">
                @csrf

                <div class="mb-2">
                    <label class="form-label small">Nama Lengkap</label>
                    <input type="text"
                        name="name"
                        value="{{ old('name') }}"
                        class="form-control rounded-pill @error('name') is-invalid @enderror"
                        placeholder="Nama lengkap Anda"
                        required>
                    @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-2">
                    <label class="form-label small">Email</label>
                    <input type="email"
                        name="email"
                        value="{{ old('email') }}"
                        class="form-control rounded-pill @error('email') is-invalid @enderror"
                        placeholder="admin@example.com"
                        required>
                    @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-2">
                    <label class="form-label small">Password</label>
                    <input type="password"
                        name="password"
                        class="form-control rounded-pill @error('password') is-invalid @enderror"
                        placeholder="Minimal 8 karakter"
                        required>
                    @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label small">Konfirmasi Password</label>
                    <input type="password"
                        name="password_confirmation"
                        class="form-control rounded-pill"
                        placeholder="Ulangi password Anda"
                        required>
                </div>

                <!-- Button -->
                <button type="submit" class="btn w-100 text-white rounded-pill" style="background-color:#4CAF50;">
                    Daftar Akun
                </button>

                <!-- Login Link -->
                <p class="text-center small mt-2 mb-0">
                    Sudah punya akun?
                    <a href="{{ route('admin.login') }}" class="fw-semibold text-decoration-none">Masuk di sini</a>
                </p>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>