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
  
    private function formatWhatsappNumber(?string $phone): ?string
    {
        if (!$phone) return null;

        $phone = preg_replace('/[^0-9]/', '', $phone);

        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }

        return $phone;
    }
  
    private function whatsappRejectMessage($user, $fee)
    {
      return urlencode(
        "Halo {$user->name},\n\n" .
        "Bukti pembayaran iuran Anda *TIDAK DAPAT KAMI TERIMA*.\n\n" .
        "Alasan:\n" .
        "- Bukti pembayaran tidak sesuai / tidak valid\n\n" .
        "Silakan upload ulang bukti pembayaran yang benar melalui aplikasi.\n\n" .
        "Terima kasih."
      );
    }



    public function validatePayment(Request $request, $id)
    {
        $request->validate([
            'payment_status' => 'required|in:success,failed',
        ]);

        $fee = MembershipFee::with('user')->findOrFail($id);

        $fee->update([
            'payment_status' => $request->payment_status,
            'validated_by'   => Auth::id(),
            'payment_date'   => $request->payment_status === 'success'
                ? now()
                : null,
        ]);

        if ($request->payment_status === 'success') {

            $fee->user->update([
                'expired_at' => now()->addYear(),
                'status'     => 'active',
            ]);

            $category = PointKategori::findOrFail(5);
            $point    = $category->point;

            UserPoint::create([
                'id_category' => $category->id,
                'id_user'     => $fee->user->id,
                'created_by'  => Auth::id(),
            ]);

            $fee->user->increment('point', $point);

            return redirect()
                ->route('membership-fee.index')
                ->with('success', 'Iuran berhasil divalidasi dan poin ditambahkan');
        }

        // =========================
        // 🚨 JIKA DITOLAK
        // =========================
        $phone = $this->formatWhatsappNumber($fee->user->phone);

        if ($phone) {
            $message = $this->whatsappRejectMessage($fee->user, $fee);

            return redirect()->away(
                "https://wa.me/{$phone}?text={$message}"
            );
        }

        return redirect()
            ->route('membership-fee.index')
            ->with('warning', 'Pembayaran ditolak, namun nomor WhatsApp tidak tersedia');
    }

}
