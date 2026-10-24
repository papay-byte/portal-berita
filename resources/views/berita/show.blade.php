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

        @if ($berita->kategori)
            <a href="{{ route('berita.index', ['kategori' => $berita->kategori->id]) }}"
               class="badge bg-success text-decoration-none mb-2">{{ $berita->kategori->nama }}</a>
        @endif

        <h1 class="h3 fw-bold">{{ $berita->judul }}</h1>

        <div class="small text-muted mb-3">
            <i class="bi bi-calendar3"></i> {{ $berita->tanggal->translatedFormat('d F Y') }}
            &nbsp;·&nbsp;
            <i class="bi bi-person"></i> {{ $berita->user->name }}
        </div>

        @if ($berita->gambar_url)
            <img src="{{ $berita->gambar_url }}" class="img-fluid rounded mb-3" alt="{{ $berita->judul }}">
        @endif

        {{-- e() mengamankan dari HTML berbahaya (XSS); nl2br mengubah baris baru jadi <br> --}}
        <div>{!! nl2br(e($berita->konten)) !!}</div>

        <div class="d-flex flex-wrap gap-1 mt-4">
            @foreach ($berita->tags as $tag)
                <a href="{{ route('berita.index', ['tag' => $tag->id]) }}"
                   class="badge bg-light text-secondary border fw-normal text-decoration-none">#{{ $tag->nama }}</a>
            @endforeach
        </div>

        <div class="d-flex flex-wrap gap-2 mt-4">
            <a href="{{ route('berita.index') }}" class="btn btn-outline-success">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>

            @if ($berita->bisaDiubahOleh(auth()->user()))
                <a href="{{ route('berita.edit', $berita) }}" class="btn btn-outline-secondary">
                    <i class="bi bi-pencil"></i> Edit
                </a>
            @endif

            @auth
                @if (auth()->user()->isAdmin())
                    <form action="{{ route('berita.destroy', $berita) }}" method="POST"
                          onsubmit="return confirm('Yakin ingin menghapus berita ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger"><i class="bi bi-trash"></i> Hapus</button>
                    </form>
                @endif
            @endauth
        </div>
    </article>

@endsection