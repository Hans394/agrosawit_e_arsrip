<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex justify-content-center align-items-center vh-100">

<div class="card shadow-sm p-4" style="max-width:400px; width:100%;">

    <h5 class="mb-2">Reset Password</h5>
    <p class="text-muted small">Masukkan password baru untuk akun Anda.</p>

    {{-- Alert Error --}}
    @if ($errors->any())
        <div class="alert alert-danger rounded-3">
            {{ $errors->first() }}
        </div>
    @endif

    <form action="{{ route('admin.password.reset') }}" method="POST">
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">
        <input type="hidden" name="email" value="{{ $email }}">

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" class="form-control"
                value="{{ $email }}" readonly>
        </div>

        <div class="mb-3">
            <label class="form-label">Password Baru</label>
            <input type="password" name="password"
                class="form-control @error('password') is-invalid @enderror"
                placeholder="Minimal 8 karakter" required>
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Konfirmasi Password</label>
            <input type="password" name="password_confirmation"
                class="form-control"
                placeholder="Ulangi password baru" required>
        </div>

        <button type="submit" class="btn btn-success w-100 mb-2">
            Reset Password
        </button>

        <a href="{{ route('admin.login') }}" class="btn btn-outline-secondary w-100">
            Kembali ke Login
        </a>
    </form>
</div>

</body>
</html>