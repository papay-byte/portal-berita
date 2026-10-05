<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Berita extends Model
{
    // Kolom baru (kategori_id, tagline) wajib ada di $fillable
    protected $fillable = [
        'kategori_id', 'judul', 'slug', 'tanggal', 'penulis',
        'ringkasan', 'konten', 'gambar', 
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    // Relasi: satu berita MILIK SATU kategori.
    // Nama method ini ('kategori') harus sama dengan yang dipanggil di with('kategori')
    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class);
    }

    // Accessor: "cikandung,dusun,2026" → ['cikandung','dusun','2026']
    public function getTaglineListAttribute(): array
    {
        if (!$this->tagline) {
            return [];
        }

        return array_filter(array_map('trim', explode(',', $this->tagline)));
    }

    // Accessor gambar: URL lengkap dipakai langsung, selain itu dianggap file di storage lokal
    public function getGambarUrlAttribute(): ?string
    {
        if (!$this->gambar) {
            return null;
        }

        return Str::startsWith($this->gambar, 'http')
            ? $this->gambar
            : asset('storage/' . $this->gambar);
    }

    // Route model binding memakai kolom 'slug', bukan 'id'
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}