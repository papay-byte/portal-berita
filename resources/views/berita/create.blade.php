@extends('layouts.app')

@section('title', 'Tambah Berita')

@section('content')
    <div class="judul-halaman mb-4">
        <h1 class="h3 fw-bold mb-0">Tambah Berita</h1>
    </div>

    {{-- enctype="multipart/form-data" wajib karena form mengirim file gambar --}}
    <form action="{{ route('berita.store') }}" method="POST" enctype="multipart/form-data"
          class="card card-body border-0 shadow-sm">
        @csrf
        @include('berita._form')
    </form>
@endsection