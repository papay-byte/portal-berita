<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(KategoriSeeder::class); // dulu: kategori
        $this->call(BeritaSeeder::class);   // lalu: berita (butuh id kategori)
    }
}