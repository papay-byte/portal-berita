@extends('layouts.app')

@section('title', 'Edit Berita')

@section('content')
    <div class="judul-halaman mb-4">
        <h1 class="h3 fw-bold mb-0">Edit Berita</h1>
    </div>

    <form action="{{ route('berita.update', $berita) }}" method="POST" enctype="multipart/form-data"
          class="card card-body border-0 shadow-sm">
        @csrf
        {{-- HTML hanya mengenal GET/POST, jadi PUT "dipalsukan" lewat @method --}}
        @method('PUT')
        @include('berita._form')
    </form>
@endsection