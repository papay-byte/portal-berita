<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kategori extends Model
{
    protected $fillable = ['nama', 'slug'];

    // Satu kategori memiliki banyak berita
    public function beritas(): HasMany
    {
        return $this->hasMany(Berita::class);
    }
}