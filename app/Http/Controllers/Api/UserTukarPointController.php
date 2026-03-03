<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TukarPoint;
use App\Models\MasterPenukaranPoin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserTukarPointController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | USER REQUEST TUKAR POIN
    |--------------------------------------------------------------------------
    */
    public function requestRedeem(Request $request)
    {
        $request->validate([
            'master_penukaran_poin_id' => 'required|exists:master_penukaran_poin,id',
        ]);

        $user = auth()->user();
        $produk = MasterPenukaranPoin::findOrFail($request->master_penukaran_poin_id);

        if ($user->point < $produk->jumlah_poin) {
            return response()->json([
                'success' => false,
                'message' => 'Poin tidak mencukupi',
                'saldo' => $user->point
            ], 422);
        }

        $redeem = TukarPoint::create([
            'user_id' => $user->id,
            'master_penukaran_poin_id' => $produk->id,
            'point' => -$produk->jumlah_poin,
            'tanggal' => now(),
            'keterangan' => 'Request tukar poin dengan ' . $produk->produk,
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Request berhasil dikirim. Menunggu approval admin.',
            'data' => $redeem
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | USER HISTORY
    |--------------------------------------------------------------------------
    */
    public function history(Request $request)
    {
        $user = auth()->user();

        $data = TukarPoint::with('masterPenukaran')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json([
            'success' => true,
            'saldo_point' => $user->point,
            'data' => $data
        ]);
    }
}