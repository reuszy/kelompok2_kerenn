<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\FamilyParent;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function authenticateLogin(Request $request)
    {
        // Cegah spam login (maks 10 kali per 5 menit per IP)
        $ip = $request->ip();
        $key = 'login_attempts:' . $ip;

        if (RateLimiter::tooManyAttempts($key, 10)) {
            $seconds = RateLimiter::availableIn($key);
            return back()->with('error', "Terlalu banyak percobaan masuk. Coba lagi dalam {$seconds} detik.");
        }

        RateLimiter::hit($key, 300); // Membuat hit bertahan selama 5 menit

        // Validasi input
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ], [
            'username.required' => 'Nama pengguna wajib diisi.',
            'password.required' => 'Kata sandi wajib diisi.'
        ]);

        $credentials = $request->only('username', 'password');

        try {
            if (Auth::attempt($credentials)) {
                $user = Auth::user();

                if (is_null($user->verified_at)) {
                    $verifiedAtMessage = (!is_null($user->parent_id))
                        ? 'Akun Anda belum diverifikasi.'
                        : 'Akun Anda tidak aktif.';

                    Auth::logout();
                    return back()->with('error', $verifiedAtMessage);
                }

                $request->session()->regenerate();

                $fullname = $user->officer_id !== null
                    ? optional($user->officers)->fullname
                    : optional($user->familyParents)->mother_fullname;

                // Hapus rate limiter jika berhasil login
                RateLimiter::clear($key);

                return redirect()->intended(url('/dashboard'))->with('success', "Selamat datang, {$fullname}.");
            }

            return back()->with('error', 'Periksa kembali nama pengguna dan kata sandi Anda.');
        } catch (\Exception $e) {
            Log::error('Error: ' . $e->getMessage()); // Check 'storage/logs/laravel.log'

            return redirect()->route('login')->with('error', 'Terjadi kesalahan saat masuk. Silahkan coba kembali.');
        }
    }

    public function register()
    {
        return view('auth.register');
    }

    public function authenticateRegister(Request $request)
    {
        $ip = $request->ip();
        $key = 'register_attempts:' . $ip;

        // Maks 5 percobaan per 5 menit
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            return back()->with('error', "Terlalu banyak percobaan pendaftaran. Coba lagi dalam {$seconds} detik.");
        }

        RateLimiter::hit($key, 300); // Membuat hit bertahan selama 5 menit

        $rules = [
            'nik' => 'required|numeric|unique:peminjams,nik',
            'fullname' => 'required',
            'username' => 'required|unique:users,username',
            'password' => 'required|min:8|confirmed',
            'phone_number' => [
                'required',
                'unique:users,phone_number',
                // Validasi tambahan
                function ($attribute, $value, $fail) {
                    if (!(substr($value, 0, 4) === '+628' || substr($value, 0, 2) === '08')) {
                        $fail('Nomor HP/WA tidak valid.');
                    }
                }
            ],
        ];

        $messages = [
            'nik.required' => 'NIK wajib diisi.',
            'nik.numeric' => 'NIK harus berupa angka.',
            'nik.unique' => 'NIK sudah terdaftar.',
            'fullname.required' => 'Nama lengkap wajib diisi.',
            'username.required' => 'Nama pengguna wajib diisi.',
            'username.unique' => 'Nama pengguna sudah digunakan.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak sesuai.',
            'phone_number.required' => 'Nomor HP/WA wajib diisi.',
            'phone_number.unique' => 'Nomor HP/WA sudah terdaftar.',
        ];

        // Konversi nomor '08' ke '+628' (sebelum validasi)
        if (substr($request->input('phone_number'), 0, 2) === '08') {
            $request->merge([
                'phone_number' => '+62' . substr($request->input('phone_number'), 1),
            ]);
        }

        $data = $request->validate($rules, $messages);
    }

    public function logout(Request $request)
    {
        Auth::logout(); // Logout user

        $request->session()->invalidate();       // Invalidate session
        $request->session()->regenerateToken();  // Regenerate CSRF token

        return redirect()->route('login')->with('success', 'Anda berhasil keluar.');
    }
}
