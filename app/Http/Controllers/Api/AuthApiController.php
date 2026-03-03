<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;


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
    $validator = \Validator::make($request->all(), [
        'name'       => 'required|string|min:3|max:255',
        'username'   => 'required|string|min:4|max:100|alpha_dash|unique:users,username',
        'email'      => 'nullable|email:rfc,dns|max:255|unique:users,email',
        'password'   => 'required|min:6',

        'role'       => 'required|in:anggota,publik',

        'phone'      => 'required|string|regex:/^([0-9\s\-\+\(\)]*)$/|max:20|unique:users,phone',

        'gender'     => 'nullable|in:L,P',
        'birth_date' => 'nullable|date|before_or_equal:' . now()->subYears(10)->toDateString(),
        'address'    => 'nullable|string|max:500',

        'city'       => 'required|string|max:255',
        'kecamatan'  => 'required|string|max:100',
        'kelurahan'  => 'required|string|max:100',

        'occupation' => 'required|string|max:255',

        'profile_photo' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        'kta_id'     => 'nullable|string|unique:users,kta_id', 
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status'  => false,
            'message' => 'Validasi gagal',
            'errors'  => $validator->errors(),
        ], 422);
    }

    try {
        $validated = $validator->validated();

        // Hitung umur
        $age = !empty($validated['birth_date'])
            ? now()->diffInYears($validated['birth_date'])
            : null;

        $isAnggota = $validated['role'] === 'anggota';
      
      	$memberType = null;

        if ($isAnggota) {
            $memberType = !empty($validated['kta_id'])
                ? 'lama'
                : 'baru';
        }

        // Upload foto
        $photoPath = null;
        if ($request->hasFile('profile_photo') && $request->file('profile_photo')->isValid()) {
            $filename = 'profile_' . Str::uuid() . '.' . $request->file('profile_photo')->extension();

            $request->file('profile_photo')->storeAs(
                'profile_photos',
                $filename,
                'public'
            );

            $photoPath = $filename;
        }

        // Create user
        $user = User::create([
            'name'          => $validated['name'],
            'username'      => $validated['username'],
            'email'         => $validated['email'] ?? null,
            'phone'         => $validated['phone'],
            'password'      => Hash::make($validated['password']),
            'gender'        => $validated['gender'] ?? null,
            'birth_date'    => $validated['birth_date'] ?? null,
            'age'           => $age,
            'address'       => $validated['address'] ?? null,
            'city'          => $validated['city'],
            'kecamatan'     => $validated['kecamatan'],
            'kelurahan'     => $validated['kelurahan'],
            'occupation'    => $validated['occupation'] ?? null,
            'join_date'     => now(),
            'profile_photo' => $photoPath,
            'role'          => $validated['role'],
            'kta_id'        => $validated['kta_id']
                                ?? ($isAnggota ? $this->generateKtaId() : null),
            'member_type'   => $memberType,
            'status'        => 'nonaktif',
            'point'         => 0,
            'expired_at'    => null,
        ]);

        $token = $user->createToken('mobile_token')->plainTextToken;

        return response()->json([
            'status'  => true,
            'message' => 'Registrasi berhasil',
            'user'    => $user->only([
                'id',
                'name',
                'username',
                'email',
                'phone',
                'role',
                'kta_id',
                'city',
                'kecamatan',
                'kelurahan',
                'profile_photo',
            ]),
            'token' => $token,
        ], 201);

    } catch (\Exception $e) {
        Log::error('Registrasi gagal', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);

        return response()->json([
            'status'  => false,
            'message' => 'Terjadi kesalahan server. Silakan coba lagi nanti.',
        ], 500);
    }
}


/**
 * LOGIN USER (username atau phone)
 * NOTE: request param tetap bernama "email" untuk kompatibilitas frontend
 */
public function login(Request $request)
{
    $request->validate([
        'email'    => 'required|string',
        'password' => 'required|string'
    ]);

    $login = trim($request->email);

    $isPhone = preg_match('/^[0-9\+\-\s\(\)]+$/', $login);

    // 🔥 JANGAN FILTER STATUS DI SINI
    $user = User::where(function ($q) use ($login, $isPhone) {
        if ($isPhone) {
            $q->where('phone', $login);
        } else {
            $q->where('username', $login);
        }
    })->first();

    // 1️⃣ USER TIDAK ADA
    if (!$user) {
        return response()->json([
            'status'  => false,
            'message' => 'Username / nomor telepon atau password salah',
            'code'    => 'INVALID_CREDENTIAL'
        ], 401);
    }

    // 3️⃣ PASSWORD SALAH
    if (!Hash::check($request->password, $user->password)) {
        return response()->json([
            'status'  => false,
            'message' => 'Username / nomor telepon atau password salah',
            'code'    => 'INVALID_CREDENTIAL'
        ], 401);
    }

    // 4️⃣ LOGIN BERHASIL
    $token = $user->createToken('mobile_token')->plainTextToken;

    return response()->json([
        'status'  => true,
        'message' => 'Login berhasil',
        'user'    => $user,
        'token'   => $token,
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
