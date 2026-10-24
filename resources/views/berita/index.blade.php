@extends('layouts.app')

@section('title', 'Berita Desa')

@section('content')

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('berita.index') }}">Beranda</a></li>
            <li class="breadcrumb-item active">Berita</li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-4">
        <div class="judul-halaman">
            <h1 class="h3 fw-bold mb-0">Berita & Informasi</h1>
            <p class="text-muted mb-0">Informasi terkini dari Desa Jalatrang</p>
        </div>

        {{-- Tombol Tambah hanya untuk yang sudah login --}}
        @auth
            <a href="{{ route('berita.create') }}" class="btn btn-hijau">
                <i class="bi bi-plus-lg"></i> Tambah Berita
            </a>
        @endauth
    </div>

    <div class="row g-4">

        {{-- ===== SIDEBAR: FILTER ===== --}}
        <aside class="col-lg-3">
            <div class="sticky-lg-top" style="top: 1rem;">
                <form action="{{ route('berita.index') }}" method="GET" class="card border-0 shadow-sm">
                    <div class="card-header bg-success text-white fw-bold">
                        <i class="bi bi-funnel-fill"></i> Filter Berita
                    </div>
                    <div class="card-body">

                        {{-- name="kata_kunci" dibaca Controller lewat $request->input('kata_kunci') --}}
                        <label for="kata_kunci" class="form-label small fw-semibold">Kata Kunci</label>
                        <input type="text" id="kata_kunci" name="kata_kunci" class="form-control mb-3"
                               placeholder="Cari berita..." value="{{ $kataKunci }}">

                        {{-- Dropdown kategori, diisi dari tabel kategoris --}}
                        <label for="kategori" class="form-label small fw-semibold">Kategori</label>
                        <select id="kategori" name="kategori" class="form-select mb-3">
                            <option value="">Semua Kategori</option>
                            @foreach ($kategoris as $kategori)
                                <option value="{{ $kategori->id }}" {{ $kategoriId == $kategori->id ? 'selected' : '' }}>
                                    {{ $kategori->nama }}
                                </option>
                            @endforeach
                        </select>

                        {{-- Dropdown tag, diisi dari tabel tags --}}
                        <label for="tag" class="form-label small fw-semibold">Tag</label>
                        <select id="tag" name="tag" class="form-select mb-3">
                            <option value="">Semua Tag</option>
                            @foreach ($tags as $tag)
                                <option value="{{ $tag->id }}" {{ $tagId == $tag->id ? 'selected' : '' }}>
                                    #{{ $tag->nama }}
                                </option>
                            @endforeach
                        </select>

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

                        <button type="submit" class="btn btn-hijau w-100 mb-2"><i class="bi bi-search"></i> Cari</button>
                        <a href="{{ route('berita.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
                    </div>
                </form>
            </div>
        </aside>

        {{-- ===== DAFTAR BERITA ===== --}}
        <div class="col-lg-9">

            @if ($kataKunci || $kategoriId || $tagId)
                <p class="text-muted small">
                    Ditemukan <strong>{{ $beritas->total() }}</strong> berita
                    @if ($kataKunci) untuk kata kunci "<strong>{{ $kataKunci }}</strong>" @endif
                </p>
            @endif

            <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-4">

                {{-- PERULANGAN: satu kartu untuk setiap berita di $beritas --}}
                @forelse ($beritas as $berita)
                    <div class="col">
                        <div class="card card-berita h-100 shadow-sm">

                            @if ($berita->gambar_url)
                                <img src="{{ $berita->gambar_url }}" class="card-img-top thumb" alt="{{ $berita->judul }}">
                            @else
                                <div class="thumb d-flex align-items-center justify-content-center text-muted">
                                    <i class="bi bi-image fs-1"></i>
                                </div>
                            @endif

                            <div class="card-body d-flex flex-column">

                                <div class="small text-muted mb-2">
                                    @if ($berita->kategori)
                                        <span class="badge bg-success">{{ $berita->kategori->nama }}</span>
                                    @endif
                                    <i class="bi bi-calendar3 ms-1"></i> {{ $berita->tanggal->translatedFormat('d M Y') }}
                                </div>

                                <h5 class="judul fw-bold fs-6">
                                    <a href="{{ route('berita.show', $berita) }}">
                                        {{ \Illuminate\Support\Str::limit($berita->judul, 80) }}
                                    </a>
                                </h5>

                                <p class="text-muted small mb-1">{{ $berita->ringkasan }}</p>
                                <p class="small text-muted"><i class="bi bi-person"></i> {{ $berita->user->name }}</p>

                                {{-- Tag (relasi Many-to-Many): klik tag = filter berita bertag itu --}}
                                <div class="d-flex flex-wrap gap-1 mb-3">
                                    @foreach ($berita->tags as $tag)
                                        <a href="{{ route('berita.index', ['tag' => $tag->id]) }}"
                                           class="badge bg-light text-secondary border fw-normal text-decoration-none">
                                            #{{ $tag->nama }}
                                        </a>
                                    @endforeach
                                </div>

                                <div class="d-flex flex-wrap gap-2 mt-auto">
                                    <a href="{{ route('berita.show', $berita) }}" class="btn btn-hijau btn-sm">
                                        Baca <i class="bi bi-arrow-right"></i>
                                    </a>

                                    {{-- Edit: admin, atau editor pemilik berita (aturan ada di Model Berita) --}}
                                    @if ($berita->bisaDiubahOleh(auth()->user()))
                                        <a href="{{ route('berita.edit', $berita) }}" class="btn btn-outline-secondary btn-sm">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                    @endif

                                    {{-- Hapus: hanya admin --}}
                                    @auth
                                        @if (auth()->user()->isAdmin())
                                            <form action="{{ route('berita.destroy', $berita) }}" method="POST"
                                                  onsubmit="return confirm('Yakin ingin menghapus berita ini? Tindakan ini tidak bisa dibatalkan.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm">
                                                    <i class="bi bi-trash"></i> Hapus
                                                </button>
                                            </form>
                                        @endif
                                    @endauth
                                </div>
                            </div>
                        </div>
                    </div>

                @empty
                    <div class="col-12">
                        <div class="text-center bg-white rounded-3 shadow-sm p-5">
                            <i class="bi bi-search display-4 text-muted"></i>

                            @if ($kataKunci || $kategoriId || $tagId)
                                <h5 class="mt-3">Berita tidak ditemukan</h5>
                                <p class="text-muted mb-3">Tidak ada berita yang cocok dengan filter Anda.</p>
                                <a href="{{ route('berita.index') }}" class="btn btn-hijau">Lihat Semua Berita</a>
                            @else
                                <h5 class="mt-3">Belum ada berita</h5>
                                <p class="text-muted mb-0">Berita akan segera ditambahkan.</p>
                            @endif
                        </div>
                    </div>
                @endforelse

            </div>

            <div class="mt-4 d-flex justify-content-center">
                {{ $beritas->links() }}
            </div>
        </div>
    </div>

@endsection