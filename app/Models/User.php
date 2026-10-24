<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // 'role' TIDAK ada di sini (mencegah orang menaikkan dirinya jadi admin lewat input)
    protected $fillable = ['name', 'email', 'password'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',   // password otomatis di-hash saat disimpan
        ];
    }

    // Relasi: satu user menulis banyak berita
    public function beritas(): HasMany
    {
        return $this->hasMany(Berita::class);
    }

    // Dipakai di middleware dan Blade: auth()->user()->isAdmin()
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}