<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kategori extends Model
{
    protected $fillable = ['nama', 'slug'];

    // Relasi: satu kategori MEMILIKI BANYAK berita.
    // Pemakaian: $kategori->beritas
    public function beritas(): HasMany
    {
        return $this->hasMany(Berita::class);
    }
}