<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Models\Kategori;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BeritaController extends Controller
{
    // ===== DAFTAR BERITA: pencarian + filter kategori + filter tag + urutan =====
    public function index(Request $request)
    {
        $kataKunci  = $request->input('kata_kunci');
        $kategoriId = $request->input('kategori');
        $tagId      = $request->input('tag');
        $urutan     = $request->input('urutan', 'terbaru');

        // Eager loading: ambil kategori, tag, dan penulis sekaligus (menghindari query berulang per kartu)
        $query = Berita::with(['kategori', 'tags', 'user']);

        // Pencarian di judul ATAU ringkasan (closure mengurung OR agar tidak merusak filter lain)
        if ($request->filled('kata_kunci')) {
            $query->where(function ($q) use ($kataKunci) {
                $q->where('judul', 'like', "%{$kataKunci}%")
                  ->orWhere('ringkasan', 'like', "%{$kataKunci}%");
            });
        }

        // Filter kategori
        if ($request->filled('kategori')) {
            $query->where('kategori_id', $kategoriId);
        }

        // Filter tag: lewat relasi, jadi memakai whereHas (cari berita yang punya tag ini)
        if ($request->filled('tag')) {
            $query->whereHas('tags', fn ($q) => $q->where('tags.id', $tagId));
        }

        switch ($urutan) {
            case 'terlama':
                $query->orderBy('tanggal', 'asc');
                break;
            case 'judul':
                $query->orderBy('judul', 'asc');
                break;
            default:
                $query->orderBy('tanggal', 'desc');
        }

        $beritas   = $query->paginate(12)->withQueryString(); // withQueryString: filter tetap ada di halaman 2, 3, ...
        $kategoris = Kategori::orderBy('nama')->get();
        $tags      = Tag::orderBy('nama')->get();

        return view('berita.index', compact(
            'beritas', 'kataKunci', 'kategoriId', 'tagId', 'urutan', 'kategoris', 'tags'
        ));
    }

    // ===== DETAIL =====
    public function show(Berita $berita)
    {
        $berita->load(['kategori', 'tags', 'user']);

        return view('berita.show', compact('berita'));
    }

    // ===== FORM TAMBAH =====
    public function create()
    {
        return view('berita.create', [
            'kategoris' => Kategori::orderBy('nama')->get(),
            'tags'      => Tag::orderBy('nama')->get(),
        ]);
    }

    // ===== SIMPAN BERITA BARU =====
    public function store(Request $request)
    {
        $data = $request->validate($this->aturan(), $this->pesan());

        $tagTerpilih = $data['tags'] ?? [];   // pisahkan tag: disimpan lewat pivot, bukan kolom beritas
        unset($data['tags'], $data['gambar']);

        $data['slug']    = $this->buatSlug($data['judul']);
        $data['user_id'] = auth()->id();      // penulis = akun yang login, BUKAN dari input form

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('berita', 'public');
        }

        $berita = Berita::create($data);
        $berita->tags()->sync($tagTerpilih);  // simpan pilihan tag ke tabel pivot berita_tag

        // Redirect (pola PRG) agar F5 tidak mengirim ulang form
        return redirect()->route('berita.index')->with('sukses', 'Berita berhasil ditambahkan!');
    }

    // ===== FORM EDIT =====
    public function edit(Berita $berita)
    {
        // 403 jika bukan admin dan bukan pemilik berita
        abort_unless($berita->bisaDiubahOleh(auth()->user()), 403, 'Anda tidak berhak mengubah berita ini.');

        $berita->load('tags');

        return view('berita.edit', [
            'berita'    => $berita,
            'kategoris' => Kategori::orderBy('nama')->get(),
            'tags'      => Tag::orderBy('nama')->get(),
        ]);
    }

    // ===== SIMPAN PERUBAHAN =====
    public function update(Request $request, Berita $berita)
    {
        abort_unless($berita->bisaDiubahOleh(auth()->user()), 403, 'Anda tidak berhak mengubah berita ini.');

        $data = $request->validate($this->aturan(), $this->pesan());

        $tagTerpilih = $data['tags'] ?? [];
        unset($data['tags'], $data['gambar']);   // gambar diisi lagi hanya jika ada file baru

        // Slug sengaja TIDAK diubah saat edit, agar link lama tidak rusak
        if ($request->hasFile('gambar')) {
            $this->hapusGambarLokal($berita->gambar);   // hapus gambar lama dari storage
            $data['gambar'] = $request->file('gambar')->store('berita', 'public');
        }

        $berita->update($data);
        $berita->tags()->sync($tagTerpilih);   // sync juga menghapus tag yang tidak dipilih lagi

        return redirect()->route('berita.show', $berita)->with('sukses', 'Berita berhasil diperbarui!');
    }

    // ===== HAPUS (hanya admin, dijaga middleware di route) =====
    public function destroy(Berita $berita)
    {
        $this->hapusGambarLokal($berita->gambar);   // hapus file gambar bila diunggah lokal
        $berita->delete();                          // baris pivot ikut terhapus (cascadeOnDelete)

        return redirect()->route('berita.index')->with('sukses', 'Berita berhasil dihapus.');
    }

    // ---------- Fungsi bantu (private: hanya dipakai di dalam controller ini) ----------

    private function aturan(): array
    {
        return [
            'judul'       => ['required', 'string', 'max:255'],
            'kategori_id' => ['required', 'exists:kategoris,id'],
            'tanggal'     => ['required', 'date'],
            'ringkasan'   => ['required', 'string', 'max:500'],
            'konten'      => ['required', 'string'],
            'gambar'      => ['nullable', 'image', 'max:2048'],   // maksimal 2 MB
            'tags'        => ['nullable', 'array'],
            'tags.*'      => ['exists:tags,id'],                  // setiap tag harus ada di tabel tags
        ];
    }

    private function pesan(): array
    {
        return [
            'required'   => 'Kolom :attribute wajib diisi.',
            'max'        => 'Kolom :attribute maksimal :max karakter.',
            'exists'     => 'Pilihan :attribute tidak valid.',
            'date'       => 'Format tanggal tidak valid.',
            'image'      => 'File harus berupa gambar (jpg, png, gif, webp).',
            'gambar.max' => 'Ukuran gambar maksimal 2 MB.',
        ];
    }

    // Slug unik: judul sama → "judul", "judul-2", "judul-3", ...
    private function buatSlug(string $judul): string
    {
        $dasar = Str::slug($judul);
        $slug  = $dasar;
        $nomor = 2;

        while (Berita::where('slug', $slug)->exists()) {
            $slug = $dasar . '-' . $nomor++;
        }

        return $slug;
    }

    // Hapus file gambar hanya jika diunggah lokal (bukan URL http dari seeder)
    private function hapusGambarLokal(?string $path): void
    {
        if ($path && !Str::startsWith($path, 'http')) {
            Storage::disk('public')->delete($path);
        }
    }
}