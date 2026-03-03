<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use Illuminate\Http\Request;

class DonationTransactionController extends Controller
{
    /**
     * =====================================================
     * LIST HISTORY TRANSAKSI DONASI
     * =====================================================
     */
    public function index(Request $request)
    {
        $query = Donation::with([
            'campaign',
            'tripayTransaction',
            'user',
        ])->orderByDesc('created_at');

        // FILTER STATUS (PAID | UNPAID | PENDING | EXPIRED)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // FILTER CAMPAIGN
        if ($request->filled('campaign_id')) {
            $query->where('campaign_id', $request->campaign_id);
        }

        // SEARCH (nama donatur / email)
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('donor_name', 'like', "%{$keyword}%")
                  ->orWhere('donor_email', 'like', "%{$keyword}%");
            });
        }

        $items = $query->paginate(15)->withQueryString();

        return view(
            'pages.master.donation-transaction.index',
            compact('items')
        );
    }

    /**
     * =====================================================
     * DETAIL TRANSAKSI DONASI
     * =====================================================
     */
    public function show(Donation $donation)
    {
        $donation->load([
            'campaign',
            'tripayTransaction',
            'user',
        ]);

        return view(
            'pages.master.donation-transaction.show',
            compact('donation')
        );
    }
}
