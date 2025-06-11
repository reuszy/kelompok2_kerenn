@extends('layouts.auth')

@section('title', 'Registrasi')

@push('styles')
@endpush

@section('main')
    <form method="POST" action="{{ route('auth-register') }}">
        @csrf


        <div class="form-group">
            <label for="fullname" class="form-label">Nama Lengkap<span class="text-danger">*</span></label>
            <input id="fullname" type="text" class="form-control @error('fullname') is-invalid @enderror"
                name="fullname" value="{{ old('fullname') }}" placeholder="Jane Doe">
            @error('fullname')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="form-group">
            <label for="phone_number">Nomor HP/WA (AKTIF) <span class="text-danger">*</span></label>
            <input id="phone_number" type="tel" class="form-control @error('phone_number') is-invalid @enderror"
                name="phone_number" value="{{ old('phone_number') }}" placeholder="+628xxxxxxxxxx">
            @error('phone_number')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="form-group">
            <label for="username" class="form-label">Nama Pengguna <span class="text-danger">*</span></label>
            <input id="username" type="text" class="form-control @error('username') is-invalid @enderror"
                name="username" value="{{ old('username') }}" placeholder="jane_doe">
            @error('username')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="form-group">
            <label for="password" class="form-label">Kata Sandi <span class="text-danger">*</span></label>
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                name="password">
            @error('password')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
            <div class="invalid-feedback" id="password-error"></div>
        </div>

        <div class="form-group">
            <label for="password_confirmation" class="form-label">Konfirmasi Kata Sandi <span
                    class="text-danger">*</span></label>
            <input id="password_confirmation" type="password"
                class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation">
            @error('password_confirmation')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
            <div class="invalid-feedback" id="password-confirmation-error"></div>
        </div>

        <button type="submit" class="btn btn-primary btn-lg w-100 mb-4">Registrasi</button>

        <div class="text-center">Sudah punya akun? <a href="{{ route('login') }}">Masuk</a></div>
    </form>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Validasi NIK
            var nikInput = document.getElementById('nik');
            var nikError = document.getElementById('nik-error');
            nikInput.addEventListener('input', function() {
                if (this.value.trim().length !== 16) {
                    nikInput.classList.add('is-invalid');
                    nikError.textContent = "NIK harus memiliki 16 karakter.";
                } else {
                    nikInput.classList.remove('is-invalid');
                    nikError.textContent = ""; // Hapus pesan error jika valid
                }
            });

            // Validasi Password
            var passwordInput = document.getElementById('password');
            var passwordError = document.getElementById('password-error');
            passwordInput.addEventListener('input', function() {
                if (this.value.length < 8) {
                    passwordInput.classList.add('is-invalid');
                    passwordError.textContent = "Kata sandi minimal 8 karakter.";
                } else {
                    passwordInput.classList.remove('is-invalid');
                    passwordError.textContent = ""; // Hapus pesan error
                }
            });

            // Validasi Konfirmasi Password
            var passwordConfirmationInput = document.getElementById('password_confirmation');
            var passwordConfirmationError = document.getElementById('password-confirmation-error');

            if (passwordConfirmationInput && passwordConfirmationError) {
                passwordConfirmationInput.addEventListener('input', function() {
                    if (this.value !== passwordInput.value) {
                        passwordConfirmationInput.classList.add('is-invalid');
                        passwordConfirmationError.textContent = "Konfirmasi kata sandi tidak sesuai.";
                    } else {
                        passwordConfirmationInput.classList.remove('is-invalid');
                        passwordConfirmationError.textContent = ""; // Hapus pesan error
                    }
                });
            }
        });
    </script>
@endpush
