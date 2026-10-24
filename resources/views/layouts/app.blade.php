<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Berita Desa') — Jalatrang</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root { --hijau: #1b7a3d; --hijau-tua: #12582b; }
        body { background: #f5f7f6; }
        .topbar { background: var(--hijau-tua); color: #fff; font-size: .85rem; }
        .navbar-desa { background: var(--hijau); }
        .navbar-desa .nav-link { color: #fff; font-weight: 500; }
        .navbar-desa .nav-link:hover, .navbar-desa .nav-link.active { color: #ffd54f; }
        .judul-halaman { border-left: 5px solid var(--hijau); padding-left: 1rem; }
        .card-berita { border: 0; border-radius: 12px; overflow: hidden; transition: .2s; }
        .card-berita:hover { transform: translateY(-4px); box-shadow: 0 8px 20px rgba(0,0,0,.12); }
        .card-berita .thumb { height: 200px; object-fit: cover; background: #dfe6e2; }
        .card-berita .judul a { color: #222; text-decoration: none; }
        .card-berita .judul a:hover { color: var(--hijau); }
        .btn-hijau { background: var(--hijau); color: #fff; }
        .btn-hijau:hover { background: var(--hijau-tua); color: #fff; }
        footer { background: var(--hijau-tua); color: #d7e7dc; }
        footer a { color: #d7e7dc; text-decoration: none; }
        footer a:hover { color: #ffd54f; }

        /* Teks berjalan */
        .running-text { overflow: hidden; white-space: nowrap; flex: 1; }
        .running-text span { display: inline-block; padding-left: 100%; animation: teks-jalan 30s linear infinite; }
        .running-text:hover span { animation-play-state: paused; }
        @keyframes teks-jalan { from { transform: translateX(0); } to { transform: translateX(-100%); } }
    </style>
</head>
<body>

    {{-- TOP BAR: info terkini (teks berjalan) + tanggal --}}
    <div class="topbar py-1">
        <div class="container d-flex align-items-center gap-3">
            <strong class="text-nowrap"><i class="bi bi-megaphone"></i> INFO TERKINI:</strong>
            <div class="running-text">
                <span>Selamat datang di website Desa Jalatrang &nbsp;✦&nbsp; Portal berita desa dengan pencarian, kategori, dan tag</span>
            </div>
            <span class="text-nowrap d-none d-md-inline">{{ now()->translatedFormat('l, j F Y') }}</span>
        </div>
    </div>

    {{-- HEADER --}}
    <header class="bg-white py-3 border-bottom">
        <div class="container d-flex align-items-center gap-3">
            <img src="https://jalatrang.id/assets/images/info_desa/logo.png" alt="Logo Desa" height="60">
            <div>
                <div class="fw-bold text-success fs-5 lh-sm">PEMERINTAH DESA JALATRANG</div>
                <small class="text-muted">KECAMATAN CIPAKU KABUPATEN CIAMIS</small>
            </div>
        </div>
    </header>

    {{-- MENU --}}
    <nav class="navbar navbar-expand-lg navbar-desa">
        <div class="container">
            <button class="navbar-toggler bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#menuUtama">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="menuUtama">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="#">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Profil</a></li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('berita.*') ? 'active' : '' }}"
                           href="{{ route('berita.index') }}">Berita</a>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="#">Potensi Wisata</a></li>
                </ul>

                {{-- Bagian kanan: berbeda untuk yang sudah login dan tamu --}}
                <ul class="navbar-nav align-items-lg-center">
                    @auth
                        <li class="nav-item">
                            <span class="nav-link">
                                <i class="bi bi-person-circle"></i>
                                {{ auth()->user()->name }} ({{ auth()->user()->role }})
                            </span>
                        </li>
                        <li class="nav-item">
                            {{-- Logout lewat POST + @csrf (bukan link biasa) --}}
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-link nav-link">Keluar</button>
                            </form>
                        </li>
                    @else
                        <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Masuk</a></li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    {{-- ISI HALAMAN --}}
    <main class="container py-4">
        {{-- Pesan sukses dari redirect()->with('sukses', ...): tampil sekali di halaman apa pun --}}
        @if (session('sukses'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('sukses') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </main>

    {{-- FOOTER --}}
    <footer class="pt-5 pb-3 mt-4">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-4">
                    <img src="https://jalatrang.id/assets/images/info_desa/logo.png" alt="Logo" height="50" class="mb-2">
                    <h6 class="text-white">PEMERINTAH DESA JALATRANG</h6>
                    <p class="small mb-0">Jalan Raya Cipaku Nomor 181<br>Desa Jalatrang, Kecamatan Cipaku<br>Kabupaten Ciamis</p>
                </div>
                <div class="col-6 col-md-4">
                    <h6 class="text-white">Menu</h6>
                    <ul class="list-unstyled small">
                        <li><a href="#">Beranda</a></li>
                        <li><a href="{{ route('berita.index') }}">Berita</a></li>
                        <li><a href="#">Profil</a></li>
                    </ul>
                </div>
                <div class="col-6 col-md-4">
                    <h6 class="text-white">Media Sosial</h6>
                    <div class="fs-4 d-flex gap-3">
                        <a href="#"><i class="bi bi-facebook"></i></a>
                        <a href="#"><i class="bi bi-youtube"></i></a>
                        <a href="#"><i class="bi bi-instagram"></i></a>
                    </div>
                </div>
            </div>
            <hr class="border-secondary mt-4">
            <p class="text-center small mb-0">© {{ date('Y') }} Pemerintah Desa Jalatrang — Semua hak dilindungi.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>