@extends('layouts.app')

@section('title', 'Masuk')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h1 class="h4 fw-bold mb-3">Masuk</h1>

                    <form action="{{ route('login.proses') }}" method="POST">
                        @csrf

                        <label for="email" class="form-label small fw-semibold">Email</label>
                        {{-- old('email') menjaga email tetap terisi jika login gagal --}}
                        <input type="email" id="email" name="email" value="{{ old('email') }}"
                               class="form-control @error('email') is-invalid @enderror" autofocus>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        <label for="password" class="form-label small fw-semibold mt-3">Password</label>
                        <input type="password" id="password" name="password"
                               class="form-control @error('password') is-invalid @enderror">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        <div class="form-check mt-3">
                            <input class="form-check-input" type="checkbox" name="ingat" id="ingat">
                            <label class="form-check-label small" for="ingat">Ingat saya</label>
                        </div>

                        <button type="submit" class="btn btn-hijau w-100 mt-3">Masuk</button>
                    </form>

                    <p class="small text-muted mt-3 mb-0">
                        Akun demo: admin@jalatrang.test atau editor@jalatrang.test (password: <code>password</code>)
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection