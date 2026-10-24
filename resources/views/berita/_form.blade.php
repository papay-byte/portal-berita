@php
    // $berita hanya ada saat EDIT. Saat TAMBAH nilainya null.
    $b = $berita ?? null;

    // Tag yang tercentang: input lama (jika validasi gagal) → tag milik berita → kosong
    $tagTerpilih = old('tags', $b ? $b->tags->pluck('id')->all() : []);
@endphp

@if ($errors->any())
    <div class="alert alert-danger">Terdapat {{ $errors->count() }} kesalahan. Periksa kembali isian di bawah.</div>
@endif

<div class="row g-3">

    <div class="col-12">
        <label for="judul" class="form-label fw-semibold">Judul</label>
        {{-- old('judul', nilaiAwal): pakai input lama jika ada, jika tidak pakai data berita --}}
        <input type="text" id="judul" name="judul" value="{{ old('judul', $b?->judul) }}"
               class="form-control @error('judul') is-invalid @enderror">
        @error('judul') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6">
        <label for="kategori_id" class="form-label fw-semibold">Kategori</label>
        <select id="kategori_id" name="kategori_id" class="form-select @error('kategori_id') is-invalid @enderror">
            <option value="">-- Pilih kategori --</option>
            @foreach ($kategoris as $kategori)
                <option value="{{ $kategori->id }}"
                    {{ old('kategori_id', $b?->kategori_id) == $kategori->id ? 'selected' : '' }}>
                    {{ $kategori->nama }}
                </option>
            @endforeach
        </select>
        @error('kategori_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6">
        <label for="tanggal" class="form-label fw-semibold">Tanggal</label>
        <input type="date" id="tanggal" name="tanggal"
               value="{{ old('tanggal', $b?->tanggal?->format('Y-m-d') ?? date('Y-m-d')) }}"
               class="form-control @error('tanggal') is-invalid @enderror">
        @error('tanggal') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-12">
        <label for="ringkasan" class="form-label fw-semibold">Ringkasan</label>
        <textarea id="ringkasan" name="ringkasan" rows="2"
                  class="form-control @error('ringkasan') is-invalid @enderror">{{ old('ringkasan', $b?->ringkasan) }}</textarea>
        @error('ringkasan') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-12">
        <label for="konten" class="form-label fw-semibold">Isi Berita</label>
        <textarea id="konten" name="konten" rows="8"
                  class="form-control @error('konten') is-invalid @enderror">{{ old('konten', $b?->konten) }}</textarea>
        @error('konten') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- TAG: checkbox. name="tags[]" mengirim banyak nilai sebagai array --}}
    <div class="col-12">
        <label class="form-label fw-semibold">Tag</label>
        <div class="d-flex flex-wrap gap-3">
            @foreach ($tags as $tag)
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="tags[]" id="tag-{{ $tag->id }}"
                           value="{{ $tag->id }}" {{ in_array($tag->id, $tagTerpilih) ? 'checked' : '' }}>
                    <label class="form-check-label" for="tag-{{ $tag->id }}">#{{ $tag->nama }}</label>
                </div>
            @endforeach
        </div>
        @error('tags.*') <div class="text-danger small">{{ $message }}</div> @enderror
    </div>

    <div class="col-12">
        <label for="gambar" class="form-label fw-semibold">Gambar (opsional)</label>
        @if ($b?->gambar_url)
            <div class="mb-2">
                <img src="{{ $b->gambar_url }}" alt="Gambar saat ini" height="100" class="rounded border">
                <div class="form-text">Gambar saat ini. Pilih file baru hanya jika ingin menggantinya.</div>
            </div>
        @endif
        <input type="file" id="gambar" name="gambar" accept="image/*"
               class="form-control @error('gambar') is-invalid @enderror">
        <div class="form-text">JPG/PNG/WebP, maksimal 2 MB.</div>
        @error('gambar') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>

<div class="d-flex gap-2 mt-4">
    <button type="submit" class="btn btn-hijau">
        <i class="bi bi-check-lg"></i> {{ $b ? 'Simpan Perubahan' : 'Simpan Berita' }}
    </button>
    <a href="{{ route('berita.index') }}" class="btn btn-outline-secondary">Batal</a>
</div>

<p class="small text-muted mt-3 mb-0">Penulis diisi otomatis sesuai akun yang sedang login.</p>