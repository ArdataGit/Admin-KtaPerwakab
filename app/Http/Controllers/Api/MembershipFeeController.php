<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MembershipFee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MembershipFeeController extends Controller
{
    /**
     * Create membership fee (manual / tripay)
     */
    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:10000',
            'type' => 'required|in:bulanan,tahunan',
            'payment_method' => 'required|in:manual,tripay',
        ]);

        $fee = MembershipFee::create([
            'user_id' => Auth::id(),
            'amount' => $request->amount,
            'type' => $request->type,
            'payment_method' => $request->payment_method,
            'payment_status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Iuran berhasil dibuat',
            'data' => $fee,
        ]);
    }

    /**
     * Upload bukti pembayaran (manual)
     */
    public function uploadProof(Request $request, $id)
    {
        $request->validate([
            'proof_image' => 'required|image|max:2048',
        ]);

        $fee = MembershipFee::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $path = $request->file('proof_image')->store('membership-fees', 'public');

        $fee->update([
            'proof_image' => $path,
            'payment_status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Bukti pembayaran berhasil diupload',
        ]);
    }

    /**
     * List iuran user
     */
    public function myFees(Request $request)
    {
        $fees = MembershipFee::where('user_id', $request->user()->id)
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($fee) {
                return [
                    'id' => $fee->id,
                    'year' => $fee->created_at->year,
                    'amount' => $fee->amount,
                    'payment_status' => $fee->payment_status,
                    'proof_image' => $fee->proof_image
                        ? asset('storage/' . $fee->proof_image)
                        : null,
                    'created_at' => $fee->created_at->toDateTimeString(),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $fees,
        ]);
    }

    public function show($id)
    {
        $fee = MembershipFee::with('user')
            ->where('id', $id)
            ->where('user_id', Auth::id()) // 🔒 pastikan milik user
            ->first();

        if (!$fee) {
            return response()->json([
                'success' => false,
                'message' => 'Data iuran tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $fee->id,
                'type' => $fee->type,
                'amount' => $fee->amount,
                'payment_method' => $fee->payment_method,
                'payment_status' => $fee->payment_status,
                'proof_image' => $fee->proof_image,
                'created_at' => $fee->created_at,
                'payment_date' => $fee->payment_date,
                'year' => $fee->created_at->year,
            ],
        ]);
    }


    /**
     * Admin validate payment
     */
    public function validatePayment(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:success,failed',
        ]);

        $fee = MembershipFee::findOrFail($id);

        $fee->update([
            'payment_status' => $request->status,
            'validated_by' => Auth::id(),
            'payment_date' => $request->status === 'success' ? now() : null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status pembayaran diperbarui',
        ]);
    }
}
