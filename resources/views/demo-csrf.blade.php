@extends('layouts.app')

@section('title', 'Demo CSRF')

@section('content')
    <h1 class="h4 fw-bold mb-3">Demo @@csrf</h1>

    {{-- Pesan sukses dari route (session flash) --}}
    @if (session('pesan'))
        <div class="alert alert-success">{{ session('pesan') }}</div>
    @endif

    <div class="row g-4">

        {{-- FORM A: DENGAN @csrf --}}
        <div class="col-md-6">
            <form action="{{ route('demo.csrf') }}" method="POST" class="card card-body shadow-sm">
                {{-- @csrf membuat input hidden berisi token rahasia milik sesi Anda --}}
                @csrf
                <h6 class="text-success fw-bold">Form A: dengan @@csrf</h6>
                <input type="text" name="nama" class="form-control mb-2" value="Budi">
                <button class="btn btn-hijau">Kirim Form A</button>
            </form>
        </div>

        {{-- FORM B: TANPA @csrf --}}
        <div class="col-md-6">
            <form action="{{ route('demo.csrf') }}" method="POST" class="card card-body shadow-sm">
                {{-- Sengaja TIDAK ada @csrf di sini --}}
                <h6 class="text-danger fw-bold">Form B: tanpa @@csrf</h6>
                <input type="text" name="nama" class="form-control mb-2" value="Budi">
                <button class="btn btn-danger">Kirim Form B</button>
            </form>
        </div>

    </div>
@endsection