@extends('layouts.app')

@section('title', 'Berita Desa')

@section('content')

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('berita.index') }}">Beranda</a></li>
            <li class="breadcrumb-item active">Berita</li>
        </ol>
    </nav>

    {{-- Judul halaman + tombol Tambah Berita di kanan --}}
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-4">
        <div class="judul-halaman">
            <h1 class="h3 fw-bold mb-0">Berita & Informasi</h1>
            <p class="text-muted mb-0">Informasi terkini dari Desa Jalatrang</p>
        </div>
        <a href="{{ route('berita.create') }}" class="btn btn-hijau">
            <i class="bi bi-plus-lg"></i> Tambah Berita
        </a>
    </div>

    {{-- Pesan sukses dari store(). Muncul sekali, hilang saat halaman di-refresh --}}
    @if (session('sukses'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('sukses') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">

        {{-- ================= SIDEBAR KIRI: FILTER ================= --}}
        <aside class="col-lg-3">
            <div class="sticky-lg-top" style="top: 1rem;">
                <form action="{{ route('berita.index') }}" method="GET" class="card border-0 shadow-sm">

                    <div class="card-header bg-success text-white fw-bold">
                        <i class="bi bi-funnel-fill"></i> Filter Berita
                    </div>

                    <div class="card-body">
                        {{-- Kata kunci (name="q") --}}
                        <label for="q" class="form-label small fw-semibold">Kata Kunci</label>
                        <input type="text" id="q" name="q" class="form-control mb-3"
                               placeholder="Cari berita..." value="{{ request('q') }}">

                        {{-- Kategori (name="kategori"): dropdown diisi dari tabel kategoris --}}
                        <label for="kategori" class="form-label small fw-semibold">Kategori</label>
                        <select id="kategori" name="kategori" class="form-select mb-3">
                            <option value="">Semua Kategori</option>
                            {{-- Perulangan: satu <option> untuk setiap kategori di database --}}
                            @foreach ($kategoris as $kategori)
                                <option value="{{ $kategori->id }}"
                                    {{ $kategoriId == $kategori->id ? 'selected' : '' }}>
                                    {{ $kategori->nama }}
                                </option>
                            @endforeach
                        </select>

                        {{-- Urutan (radio) --}}
                        <div class="small fw-semibold mb-2">Urutkan</div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="urutan" id="u-terbaru"
                                   value="terbaru" {{ $urutan == 'terbaru' ? 'checked' : '' }}>
                            <label class="form-check-label" for="u-terbaru">Terbaru</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="urutan" id="u-terlama"
                                   value="terlama" {{ $urutan == 'terlama' ? 'checked' : '' }}>
                            <label class="form-check-label" for="u-terlama">Terlama</label>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="urutan" id="u-judul"
                                   value="judul" {{ $urutan == 'judul' ? 'checked' : '' }}>
                            <label class="form-check-label" for="u-judul">Judul (A-Z)</label>
                        </div>

                        <button type="submit" class="btn btn-hijau w-100 mb-2">
                            <i class="bi bi-search"></i> Cari
                        </button>
                        <a href="{{ route('berita.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
                    </div>
                </form>
            </div>
        </aside>

        {{-- ================= KOLOM KANAN: DAFTAR BERITA ================= --}}
        <div class="col-lg-9">

            {{-- Info hasil saat ada filter aktif --}}
            @if ($kataKunci || $kategoriId)
                <p class="text-muted small">
                    Ditemukan <strong>{{ $beritas->total() }}</strong> berita
                    @if ($kataKunci) untuk kata kunci "<strong>{{ $kataKunci }}</strong>" @endif
                </p>
            @endif

            <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-4">

                @forelse ($beritas as $berita)
                    <div class="col">
                        <div class="card card-berita h-100 shadow-sm">

                            <img src="{{ $berita->gambar_url }}" class="card-img-top thumb" alt="{{ $berita->judul }}">

                            <div class="card-body d-flex flex-column">

                                {{-- Badge kategori + tanggal --}}
                                <div class="small text-muted mb-2">
                                    {{-- @if: berita bisa tanpa kategori (kategori_id NULL) --}}
                                    @if ($berita->kategori)
                                        <span class="badge bg-success">{{ $berita->kategori->nama }}</span>
                                    @endif
                                    <i class="bi bi-calendar3 ms-1"></i> {{ $berita->tanggal->translatedFormat('d M Y') }}
                                </div>

                                <h5 class="judul fw-bold fs-6">
                                    <a href="{{ route('berita.show', $berita->slug) }}">
                                        {{ \Illuminate\Support\Str::limit($berita->judul, 80) }}
                                    </a>
                                </h5>

                                <p class="text-muted small">{{ $berita->ringkasan }}</p>

                                {{-- Tagline: perulangan hashtag. Klik hashtag = cari berita dengan tag itu --}}
                                <div class="d-flex flex-wrap gap-1 mb-3">
                                    @foreach ($berita->tagline_list as $tag)
                                        <a href="{{ route('berita.index', ['q' => $tag]) }}"
                                           class="badge bg-light text-secondary border fw-normal text-decoration-none">
                                            #{{ $tag }}
                                        </a>
                                    @endforeach
                                </div>

                                <a href="{{ route('berita.show', $berita->slug) }}"
                                   class="btn btn-hijau btn-sm align-self-start mt-auto">
                                    Baca <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                @empty
                    <div class="col-12">
                        <div class="text-center bg-white rounded-3 shadow-sm p-5">
                            <i class="bi bi-search display-4 text-muted"></i>

                            @if ($kataKunci || $kategoriId)
                                <h5 class="mt-3">Berita tidak ditemukan</h5>
                                <p class="text-muted mb-3">
                                    Tidak ada berita yang cocok dengan filter Anda.
                                    Coba kata kunci lain atau pilih "Semua Kategori".
                                </p>
                                <a href="{{ route('berita.index') }}" class="btn btn-hijau">Lihat Semua Berita</a>
                            @else
                                <h5 class="mt-3">Belum ada berita</h5>
                                <p class="text-muted mb-3">Jadilah yang pertama menambahkan berita.</p>
                                <a href="{{ route('berita.create') }}" class="btn btn-hijau">Tambah Berita</a>
                            @endif
                        </div>
                    </div>
                @endforelse

            </div>

            {{-- Pagination mempertahankan ?q=...&kategori=... lewat withQueryString() di Controller --}}
            <div class="mt-4 d-flex justify-content-center">
                {{ $beritas->links() }}
            </div>
        </div>
    </div>

@endsection