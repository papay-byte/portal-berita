<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        // Tag diambil dari contoh hashtag di situs referensi
        $daftar = ['cikandung', 'dusun', '2026', 'desa', 'jalatrang', 'siaga', 'voli', 'bola', 'turnamen'];

        foreach ($daftar as $nama) {
            Tag::create(['nama' => $nama, 'slug' => Str::slug($nama)]);
        }
    }
}