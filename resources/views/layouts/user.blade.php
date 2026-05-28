<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body style="background-color:#F5F5F5;">

    <!-- NAVBAR -->
    <nav class="navbar navbar-light fixed-top" style="background-color: #D8E9A8;">
        <div class="container-fluid d-flex justify-content-between align-items-center">

            <!-- ─── KIRI: Hamburger + Judul ─────────────────────── -->
            <div class="d-flex align-items-center">
                <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas"
                    data-bs-target="#offcanvasUser" aria-controls="offcanvasUser">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="ms-2">
                    <div class="fw-bold">@yield('page_title', 'Dashboard User')</div>
                    <small>Selamat Datang Kembali!!</small>
                </div>
            </div>

            <!-- ─── TENGAH: Search Bar ───────────────────────────── -->
            {{-- ✅ Action diubah ke route search_user --}}
            <form action="{{ route('search_user') }}" method="GET"
                class="d-none d-md-flex align-items-center"
                style="flex: 1; max-width: 400px; margin: 0 20px;">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 rounded-start-pill">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text"
                           name="cari"
                           value="{{ request('cari') }}"
                           class="form-control border-start-0 rounded-end-pill"
                           placeholder="Cari Arsip, Agenda, Laporan..."
                           style="box-shadow: none;">
                </div>
            </form>

            <!-- ─── KANAN: Notifikasi + Divider + Logo + Nama ────── -->
            <div class="d-flex align-items-center gap-3">

                {{-- Icon Notifikasi --}}
                <div class="position-relative" style="cursor:pointer;">
                    <i class="bi bi-bell fs-5"></i>
                </div>

                {{-- Divider --}}
                <div style="width:1px; height:30px; background-color:#aaa;"></div>

                {{-- Logo + Nama User --}}
                <div class="d-flex align-items-center gap-2">
                    <img src="{{ asset('images/logo.png') }}" alt="logo"
                        width="30" height="30"
                        class="rounded-circle"
                        style="object-fit:cover;">
                    <span class="fw-semibold small">
                        {{ Auth::guard('web')->user()?->name ?? 'User' }}
                    </span>
                </div>

            </div>
        </div>
    </nav>

    <!-- SIDEBAR / OFFCANVAS -->
    <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasUser">

        <!-- HEADER -->
        <div class="offcanvas-header" style="background-color:#D8E9A8;">
            <h5 class="fw-bold">
                <i class="bi bi-folder2"></i> Arsip Digital
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>

        <div class="px-3 pb-2" style="background-color:#D8E9A8;">
            <small>User Panel</small>
        </div>

        <!-- MENU -->
        <div class="offcanvas-body" style="background-color:#D8E9A8;">

            {{-- Search Bar Mobile (tampil di sidebar untuk layar kecil) --}}
            <form action="{{ route('search_user') }}" method="GET" class="mb-3 d-md-none">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 rounded-start-pill">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text"
                           name="cari"
                           value="{{ request('cari') }}"
                           class="form-control border-start-0 rounded-end-pill"
                           placeholder="Cari Arsip, Agenda, Laporan..."
                           style="box-shadow: none;">
                </div>
            </form>

            <ul class="navbar-nav">

                <li class="nav-item">
                    <a class="nav-link {{ Request::is('dashboard_user') ? 'active' : '' }}"
                        href="{{ route('dashboard_user') }}">
                        <i class="bi bi-grid"></i> Dashboard
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ Request::is('input_arsip_user') ? 'active' : '' }}"
                        href="{{ route('input_arsip_user') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="black"
                            class="bi bi-file-earmark-plus" viewBox="0 0 16 16">
                            <path d="M8 6.5a.5.5 0 0 1 .5.5v1.5H10a.5.5 0 0 1 0 1H8.5V11a.5.5 0 0 1-1 0V9.5H6a.5.5 0 0 1 0-1h1.5V7a.5.5 0 0 1 .5-.5" />
                            <path d="M14 4.5V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h5.5zm-3 0A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V4.5z" />
                        </svg>
                        Input Arsip
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ Request::is('data_arsip_user') ? 'active' : '' }}"
                        href="{{ route('data_arsip_user') }}">
                        <i class="bi bi-file-earmark-text"></i> Data Arsip
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ Request::is('buku_agenda_user') ? 'active' : '' }}"
                        href="{{ route('buku_agenda_user') }}">
                        <i class="bi bi-book"></i> Buku Agenda
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ Request::is('laporan_dan_analisis_user') ? 'active' : '' }}"
                        href="{{ route('laporan_dan_analisis_user') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                            fill="currentColor" class="bi bi-bar-chart" viewBox="0 0 16 16">
                            <path d="M4 11H2v3h2zm5-4H7v7h2zm5-5v12h-2V2zm-2-1a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1zM6 7a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1zm-5 4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1z" />
                        </svg>
                        Laporan dan Analisis
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ Request::is('profil_user') ? 'active' : '' }}"
                        href="{{ route('profil_user') }}">
                        <i class="bi bi-person"></i> Profil User
                    </a>
                </li>

                {{-- TOMBOL LOGOUT --}}
                <li class="nav-item mt-4">
                    <form action="{{ route('user.logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="nav-link border-0 bg-transparent text-start w-100 text-danger">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </button>
                    </form>
                </li>

            </ul>
        </div>
    </div>

    <!-- CONTENT -->
    <main class="container mt-5 pt-5">
        @yield('content')
    </main>

</body>

</html>