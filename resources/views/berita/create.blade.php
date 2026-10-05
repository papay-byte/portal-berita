@extends('layouts.app')

@section('title', 'Tambah Berita')

@section('content')

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('berita.index') }}">Beranda</a></li>
            <li class="breadcrumb-item"><a href="{{ route('berita.index') }}">Berita</a></li>
            <li class="breadcrumb-item active">Tambah</li>
        </ol>
    </nav>

    <div class="judul-halaman mb-4">
        <h1 class="h3 fw-bold mb-0">Tambah Berita</h1>
        <p class="text-muted mb-0">Isi form berikut untuk menerbitkan berita baru</p>
    </div>

    {{-- enctype="multipart/form-data" WAJIB jika form mengirim file (gambar).
         Tanpa ini, file tidak ikut terkirim. --}}
    <form action="{{ route('berita.store') }}" method="POST" enctype="multipart/form-data"
          class="card card-body border-0 shadow-sm">

        {{-- Token CSRF: wajib di setiap form POST --}}
        @csrf

        {{-- Ringkasan semua error di bagian atas --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                Terdapat {{ $errors->count() }} kesalahan. Periksa kembali isian di bawah.
            </div>
        @endif

        <div class="row g-3">

            {{-- JUDUL. old('judul') mengisi ulang nilai lama jika validasi gagal.
                 @error('judul') ... @enderror aktif hanya jika kolom judul punya error --}}
            <div class="col-12">
                <label for="judul" class="form-label fw-semibold">Judul</label>
                <input type="text" id="judul" name="judul"
                       class="form-control @error('judul') is-invalid @enderror"
                       value="{{ old('judul') }}">
                @error('judul')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- KATEGORI: dropdown diisi dari tabel kategoris --}}
            <div class="col-md-4">
                <label for="kategori_id" class="form-label fw-semibold">Kategori</label>
                <select id="kategori_id" name="kategori_id"
                        class="form-select @error('kategori_id') is-invalid @enderror">
                    <option value="">-- Pilih kategori --</option>
                    @foreach ($kategoris as $kategori)
                        <option value="{{ $kategori->id }}"
                            {{ old('kategori_id') == $kategori->id ? 'selected' : '' }}>
                            {{ $kategori->nama }}
                        </option>
                    @endforeach
                </select>
                @error('kategori_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- TANGGAL: default hari ini --}}
            <div class="col-md-4">
                <label for="tanggal" class="form-label fw-semibold">Tanggal</label>
                <input type="date" id="tanggal" name="tanggal"
                       class="form-control @error('tanggal') is-invalid @enderror"
                       value="{{ old('tanggal', date('Y-m-d')) }}">
                @error('tanggal')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- PENULIS --}}
            <div class="col-md-4">
                <label for="penulis" class="form-label fw-semibold">Penulis</label>
                <input type="text" id="penulis" name="penulis"
                       class="form-control @error('penulis') is-invalid @enderror"
                       value="{{ old('penulis', 'JalatrangNews') }}">
                @error('penulis')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- RINGKASAN --}}
            <div class="col-12">
                <label for="ringkasan" class="form-label fw-semibold">Ringkasan</label>
                <textarea id="ringkasan" name="ringkasan" rows="2"
                          class="form-control @error('ringkasan') is-invalid @enderror">{{ old('ringkasan') }}</textarea>
                @error('ringkasan')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- KONTEN --}}
            <div class="col-12">
                <label for="konten" class="form-label fw-semibold">Isi Berita</label>
                <textarea id="konten" name="konten" rows="8"
                          class="form-control @error('konten') is-invalid @enderror">{{ old('konten') }}</textarea>
                @error('konten')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- TAGLINE --}}
            <div class="col-md-6">
                <label for="tagline" class="form-label fw-semibold">Tagline (hashtag)</label>
                <input type="text" id="tagline" name="tagline"
                       class="form-control @error('tagline') is-invalid @enderror"
                       placeholder="contoh: voli, turnamen, 2026" value="{{ old('tagline') }}">
                <div class="form-text">Pisahkan dengan koma. Tanda # tidak perlu diketik.</div>
                @error('tagline')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- GAMBAR (opsional). Input file tidak bisa diisi ulang oleh old() --}}
            <div class="col-md-6">
                <label for="gambar" class="form-label fw-semibold">Gambar (opsional)</label>
                <input type="file" id="gambar" name="gambar" accept="image/*"
                       class="form-control @error('gambar') is-invalid @enderror">
                <div class="form-text">Format JPG/PNG/WebP, maksimal 2 MB.</div>
                @error('gambar')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-hijau"><i class="bi bi-check-lg"></i> Simpan Berita</button>
            <a href="{{ route('berita.index') }}" class="btn btn-outline-secondary">Batal</a>
        </div>
    </form>

@endsection