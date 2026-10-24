<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,      // 1. user (penulis berita)
            KategoriSeeder::class,  // 2. kategori
            TagSeeder::class,       // 3. tag
            BeritaSeeder::class,    // 4. berita (butuh user, kategori, tag)
        ]);
    }
}