<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Models\User;
use Carbon\Carbon;

class ForgotPasswordController extends Controller
{
    // API: Request reset password (kirim email)
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ], [
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'email.exists' => 'Email tidak terdaftar'
        ]);

        // Generate token unik
        $token = Str::random(64);

        // Hapus token lama untuk email ini
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        // Simpan token baru
        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => $token,
            'used' => false,
            'created_at' => Carbon::now()
        ]);

        // URL frontend untuk reset password
        $frontendUrl = env('FRONTEND_URL', 'http://localhost:3000');
        $resetLink = $frontendUrl . '/reset-password?token=' . $token;
        
        // Kirim email
        Mail::send('emails.reset-password', ['resetLink' => $resetLink], function($message) use($request) {
            $message->to($request->email);
            $message->subject('Reset Password');
        });

        return response()->json([
            'success' => true,
            'message' => 'Link reset password telah dikirim ke email Anda'
        ], 200);
    }

    // API: Validasi token (opsional, untuk cek token sebelum submit form)
    public function validateToken(Request $request)
    {
        $request->validate([
            'token' => 'required'
        ]);

        $resetToken = DB::table('password_reset_tokens')
            ->where('token', $request->token)
            ->where('used', false)
            ->first();

        if (!$resetToken) {
            return response()->json([
                'success' => false,
                'message' => 'Token tidak valid atau sudah digunakan'
            ], 400);
        }

        // Cek apakah token sudah expired (60 menit)
        $createdAt = Carbon::parse($resetToken->created_at);
        if (Carbon::now()->diffInMinutes($createdAt) > 60) {
            return response()->json([
                'success' => false,
                'message' => 'Token sudah kadaluarsa'
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Token valid',
            'data' => [
                'email' => $resetToken->email
            ]
        ], 200);
    }

    // API: Reset password
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'password' => 'required|min:8|confirmed'
        ], [
            'token.required' => 'Token harus diisi',
            'password.required' => 'Password harus diisi',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok'
        ]);

        // Cek token
        $resetToken = DB::table('password_reset_tokens')
            ->where('token', $request->token)
            ->where('used', false)
            ->first();

        if (!$resetToken) {
            return response()->json([
                'success' => false,
                'message' => 'Token tidak valid atau sudah digunakan'
            ], 400);
        }

        // Cek expiry
        $createdAt = Carbon::parse($resetToken->created_at);
        if (Carbon::now()->diffInMinutes($createdAt) > 60) {
            return response()->json([
                'success' => false,
                'message' => 'Token sudah kadaluarsa'
            ], 400);
        }

        // Update password
        User::where('email', $resetToken->email)
            ->update(['password' => Hash::make($request->password)]);

        // Tandai token sebagai sudah digunakan
        DB::table('password_reset_tokens')
            ->where('token', $request->token)
            ->update(['used' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Password berhasil diubah. Silakan login dengan password baru'
        ], 200);
    }
}
