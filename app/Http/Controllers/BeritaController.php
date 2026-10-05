<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BeritaController extends Controller
{
    // ===== DAFTAR BERITA + PENCARIAN + FILTER KATEGORI + SORTIR =====
    public function index(Request $request)
    {
        $kataKunci  = $request->input('q');                  // kata kunci dari form
        $kategoriId = $request->input('kategori');           // id kategori dari dropdown
        $urutan     = $request->input('urutan', 'terbaru');  // urutan, default terbaru

        // with('kategori') = ambil data kategori sekaligus (eager loading),
        // supaya tidak ada query tambahan untuk setiap kartu di perulangan
        $query = Berita::with('kategori');

        // Filter kata kunci: cari di judul, ringkasan, ATAU tagline
        if ($request->filled('q')) {
            $query->where(function ($q) use ($kataKunci) {
                $q->where('judul', 'like', "%{$kataKunci}%")
                  ->orWhere('ringkasan', 'like', "%{$kataKunci}%")
                  ->orWhere('tagline', 'like', "%{$kataKunci}%");
            });
        }

        // Filter kategori: hanya jika dropdown memilih kategori tertentu
        if ($request->filled('kategori')) {
            $query->where('kategori_id', $kategoriId);
        }

        // Urutan data
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

        $beritas = $query->paginate(12)->withQueryString();

        // Daftar kategori untuk mengisi dropdown di sidebar
        $kategoris = Kategori::orderBy('nama')->get();

        return view('berita.index', compact('beritas', 'kataKunci', 'urutan', 'kategoris', 'kategoriId'));
    }

    // ===== MENAMPILKAN FORM TAMBAH BERITA (GET /berita/tambah) =====
    public function create()
    {
        // Kategori dibutuhkan untuk pilihan dropdown di form
        $kategoris = Kategori::orderBy('nama')->get();

        return view('berita.create', compact('kategoris'));
    }

    // ===== MENYIMPAN BERITA BARU (POST /berita) =====
    public function store(Request $request)
    {
        // validate(): jika ada yang tidak memenuhi aturan, Laravel otomatis
        // redirect kembali ke form + membawa pesan error + input lama (old())
        $data = $request->validate([
            'judul'       => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategoris,id',  // harus ada di tabel kategoris
            'tanggal'     => 'required|date',
            'penulis'     => 'required|string|max:100',
            'ringkasan'   => 'required|string|max:500',
            'konten'      => 'required|string',
            'tagline'     => 'nullable|string|max:150',
            'gambar'      => 'nullable|image|max:2048',       // opsional, gambar maksimal 2 MB
        ], [
            // Pesan error dalam bahasa Indonesia
            'required'     => 'Kolom :attribute wajib diisi.',
            'max'          => 'Kolom :attribute maksimal :max karakter.',
            'exists'       => 'Kategori yang dipilih tidak valid.',
            'date'         => 'Format tanggal tidak valid.',
            'image'        => 'File harus berupa gambar (jpg, png, gif, webp).',
            'gambar.max'   => 'Ukuran gambar maksimal 2 MB.',
        ]);

        // Buat slug dari judul, lalu pastikan unik (kolom slug bersifat unique)
        $slugAsli = Str::slug($data['judul']);
        $slug     = $slugAsli;
        $nomor    = 2;
        while (Berita::where('slug', $slug)->exists()) {
            $slug = $slugAsli . '-' . $nomor++;   // judul sama → "judul-2", "judul-3", ...
        }
        $data['slug'] = $slug;

        // Rapikan tagline: "Cikandung, #Dusun , 2026" → "cikandung,dusun,2026"
        if (!empty($data['tagline'])) {
            $tags = array_map(fn ($t) => Str::slug(ltrim(trim($t), '#')), explode(',', $data['tagline']));
            $data['tagline'] = implode(',', array_filter($tags));
        }

        // Upload gambar (jika ada): disimpan di storage/app/public/berita/...
        // lalu path-nya (mis. "berita/abc123.jpg") disimpan ke database
        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('berita', 'public');
        }

        Berita::create($data);   // simpan ke database (semua kolom ada di $fillable)

        // Redirect (bukan return view) agar F5 tidak mengirim ulang form (pola PRG)
        return redirect()->route('berita.index')->with('sukses', 'Berita berhasil ditambahkan!');
    }

    // ===== DETAIL BERITA =====
    public function show(Berita $berita)
    {
        return view('berita.show', compact('berita'));
    }
}