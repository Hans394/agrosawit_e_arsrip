<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Login Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Icon Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body>
    <!-- Background -->
    <div class="d-flex justify-content-center align-items-center vh-100"
        style="background-image: url('{{ asset('images/bg.jpeg') }}'); 
            background-size: cover; 
            background-position: center; 
            background-repeat: no-repeat;">
        <!-- Overlay biar lebih soft -->
        <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark opacity-25"></div>
        <!-- Card -->
        <div class="card border-0 shadow-lg p-4 position-relative"
            style="width: 420px; background-color: #cfe3a5; border-radius: 20px;">
            <!-- Logo -->
            <div class="text-center mb-3">
                <img src="{{ asset('images/logo.png') }}" width="50" height="50"
                    class="rounded-circle bg-light p-1">
                <h5 class="fw-semibold mt-2">Login Admin</h5>
            </div>

            {{-- Alert Error (validasi / kredensial salah) --}}
            @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show rounded-3" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-1"></i>
                {{ $errors->first() }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            {{-- Alert Sukses (misal setelah logout) --}}
            @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
                <i class="bi bi-check-circle-fill me-1"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            <!-- Form — METHOD POST + @csrf -->
            <form action="{{ route('admin.login.post') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email"
                        name="email"
                        value="{{ old('email') }}"
                        class="form-control rounded-pill @error('email') is-invalid @enderror"
                        placeholder="admin@example.com"
                        required autofocus>
                    @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-2">
                    <label class="form-label">Password</label>
                    <input type="password"
                        name="password"
                        class="form-control rounded-pill @error('password') is-invalid @enderror"
                        placeholder="••••••••"
                        required>
                    @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <!-- Remember & Forgot -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember"
                            {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label" for="remember">ingat saya</label>
                    </div>
                    <a href="{{ route('admin.forgot-password') }}" class="small text-decoration-none">Lupa Password?</a>
                </div>
                <!-- Button -->
                <button type="submit" class="btn w-100 rounded-pill mb-3 text-white" style="background-color:#4CAF50;">
                    Login
                </button>
                <!-- Register -->
                <p class="text-center small mb-0">
                    Belum punya akun?
                    <a href="{{ route('admin.register') }}" class="text-decoration-none fw-semibold">Daftar di sini</a>
                </p>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>