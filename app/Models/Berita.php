<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Berita extends Model
{
    protected $fillable = [
        'kategori_id', 'user_id', 'judul', 'slug', 'tanggal',
        'ringkasan', 'konten', 'gambar',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    // Berita milik satu kategori. Pemakaian: $berita->kategori->nama
    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class);
    }

    // Berita ditulis satu user. Pemakaian: $berita->user->name
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Many-to-Many: berita punya banyak tag lewat tabel pivot berita_tag
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    // Aturan hak akses: admin boleh semua, editor hanya boleh mengubah beritanya sendiri.
    // Dipakai di Controller dan Blade supaya aturannya hanya ada di satu tempat.
    public function bisaDiubahOleh(?User $user): bool
    {
        return $user !== null && ($user->isAdmin() || $user->id === $this->user_id);
    }

    // Accessor: URL lengkap dipakai langsung, selain itu dianggap file di storage lokal
    public function getGambarUrlAttribute(): ?string
    {
        if (!$this->gambar) {
            return null;
        }

        return Str::startsWith($this->gambar, 'http')
            ? $this->gambar
            : asset('storage/' . $this->gambar);
    }

    // Route model binding memakai slug, bukan id
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}