@extends('layouts.app')

@section('title', $berita->judul)

@section('content')

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('berita.index') }}">Beranda</a></li>
            <li class="breadcrumb-item"><a href="{{ route('berita.index') }}">Berita</a></li>
            <li class="breadcrumb-item active">Detail</li>
        </ol>
    </nav>

    <article class="bg-white p-4 rounded-3 shadow-sm">

        {{-- Badge kategori (hanya jika berita punya kategori) --}}
        @if ($berita->kategori)
            <a href="{{ route('berita.index', ['kategori' => $berita->kategori->id]) }}"
               class="badge bg-success text-decoration-none mb-2">{{ $berita->kategori->nama }}</a>
        @endif

        <h1 class="h3 fw-bold">{{ $berita->judul }}</h1>

        <div class="small text-muted mb-3">
            <i class="bi bi-calendar3"></i> {{ $berita->tanggal->translatedFormat('d F Y') }}
            &nbsp;·&nbsp;
            <i class="bi bi-person"></i> {{ $berita->penulis }}
        </div>

        @if ($berita->gambar_url)
            <img src="{{ $berita->gambar_url }}" class="img-fluid rounded mb-3" alt="{{ $berita->judul }}">
        @endif

        {{-- e() mengamankan teks dari HTML berbahaya (XSS); nl2br mengubah baris baru jadi <br> --}}
        <div>{!! nl2br(e($berita->konten)) !!}</div>

        {{-- Tagline --}}
        <div class="d-flex flex-wrap gap-1 mt-4">
            @foreach ($berita->tagline_list as $tag)
                <a href="{{ route('berita.index', ['q' => $tag]) }}"
                   class="badge bg-light text-secondary border fw-normal text-decoration-none">#{{ $tag }}</a>
            @endforeach
        </div>

        <a href="{{ route('berita.index') }}" class="btn btn-outline-success mt-4">
            <i class="bi bi-arrow-left"></i> Kembali ke daftar berita
        </a>
    </article>

@endsection