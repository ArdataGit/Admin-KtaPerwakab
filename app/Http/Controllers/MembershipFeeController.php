<?php

namespace App\Http\Controllers;

use App\Models\MembershipFee;
use App\Models\PointKategori;
use App\Models\UserPoint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MembershipFeeController extends Controller
{
    /**
     * List semua iuran
     */
    public function index(Request $request)
    {
        $query = MembershipFee::with(['user', 'validator']);

        // Filter status pembayaran
        if ($request->filled('status')) {
            $query->where('payment_status', $request->status);
        }

        // Filter jenis iuran
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Search nama / email anggota
        if ($request->filled('search')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $fees = $query
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('pages.membership-fee.index', compact('fees'));
    }

    /**
     * Detail iuran
     */
    public function show($id)
    {
        $fee = MembershipFee::with(['user', 'validator'])->findOrFail($id);

        return view('pages.membership-fee.show', compact('fee'));
    }

    /**
     * Validasi iuran (admin)
     */
    public function validatePayment(Request $request, $id)
    {
        $request->validate([
            'payment_status' => 'required|in:success,failed',
        ]);

        $fee = MembershipFee::with('user')->findOrFail($id);

        $fee->update([
            'payment_status' => $request->payment_status,
            'validated_by' => Auth::id(),
            'payment_date' => $request->payment_status === 'success'
                ? now()
                : null,
        ]);

        // OPTIONAL: jika sukses, aktifkan & perpanjang anggota
        if ($request->payment_status === 'success') {
            $fee->user->update([
                'expired_at' => now()->addYear(),
                'status' => 'active',
            ]);

            // Tambahkan point ke user dengan kategori ID 5
            $category = PointKategori::findOrFail(5);
            $point = $category->point;

            UserPoint::create([
                'id_category' => $category->id,
                'id_user' => $fee->user->id,
                'created_by' => Auth::id(),
            ]);

            $fee->user->increment('point', $point);
        }

        return redirect()
            ->route('membership-fee.index', $fee->id)
            ->with('success', 'Status iuran berhasil diperbarui');
    }
}
