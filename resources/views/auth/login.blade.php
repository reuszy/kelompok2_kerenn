@extends('layouts.auth')

@section('title', 'Masuk')

@push('styles')
@endpush

@section('main')
    <form method="POST" action="{{ route('auth-login') }}">
        @csrf
        <div class="form-group">
            <label for="username" class="form-label">Nama Pengguna</label>
            <input id="username" type="text" class="form-control @error('username') is-invalid @enderror" name="username"
                placeholder="Masukkan nama pengguna" autofocus>
            @error('username')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="form-group">
            <label for="password" class="form-label">Kata Sandi</label>
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                name="password" placeholder="Masukkan kata sandi">
            @error('password')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary btn-lg w-100 mb-4">Masuk</button>
    </form>
@endsection

@push('scripts')
@endpush
