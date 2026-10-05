<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        // "Olahraga" dan "Pendidikan" terlihat di situs referensi.
        // Tiga lainnya adalah contoh tambahan, bebas Anda ubah.
        $daftar = ['Olahraga', 'Pendidikan', 'Kesehatan', 'Pemerintahan', 'Sosial'];

        foreach ($daftar as $nama) {
            Kategori::create([
                'nama' => $nama,
                'slug' => \Illuminate\Support\Str::slug($nama), // "Olahraga" → "olahraga"
            ]);
        }
    }
}