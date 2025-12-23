<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class AuthApiController extends Controller
{

    /**
     * REGISTER USER
     */
    private function generateKtaId(): string
    {
        do {
            $numbers = str_pad((string) random_int(0, 9999999), 7, '0', STR_PAD_LEFT);
            $letter = chr(random_int(65, 90)); // A-Z

            $kta = 'KTA-' . $numbers . $letter;
        } while (
            \App\Models\User::where('kta_id', $kta)->exists()
        );

        return $kta;
    }


    /**
     * REGISTER USER
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required|in:anggota,publik',

            'phone' => 'nullable|string|max:20',
            'gender' => 'nullable|string|in:L,P',
            'birth_date' => 'nullable|date',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'occupation' => 'nullable|string',
        ]);

        // Hitung umur
        $age = $request->birth_date
            ? now()->diffInYears($request->birth_date)
            : null;

        $isAnggota = $request->role === 'anggota';

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),

            'phone' => $request->phone,
            'gender' => $request->gender,
            'birth_date' => $request->birth_date,
            'age' => $age,
            'address' => $request->address,
            'city' => $request->city,
            'occupation' => $request->occupation,

            'join_date' => now(),
            'profile_photo' => null,

            'role' => $request->role,
            'kta_id' => $isAnggota ? $this->generateKtaId() : null,

            'status' => 'nonaktif',
            'point' => 0,
            'expired_at' => null,
        ]);

        $token = $user->createToken('mobile_token')->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'Registrasi berhasil',
            'user' => $user,
            'token' => $token,
        ], 201);
    }


    /**
     * LOGIN USER
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        // Cek email & password
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Email atau password salah',
            ], 401);
        }

        // Buat token jika lolos semua pengecekan
        $token = $user->createToken('mobile_token')->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'Login berhasil',
            'user' => $user,
            'token' => $token
        ]);
    }


    /**
     * LOGOUT USER
     */
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'status' => true,
            'message' => 'Logout berhasil'
        ]);
    }
}
