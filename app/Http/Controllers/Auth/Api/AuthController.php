<?php

namespace App\Http\Controllers\Auth\Api;

use App\Http\Controllers\Controller;
// use App\Models\FamilyParent;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function authenticateLogin(Request $request)
    {
        $credentials = $request->only('username', 'password');

        // Gunakan guard api yang driver jwt
        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => config('jwt.ttl') * 60,
        ]);
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
