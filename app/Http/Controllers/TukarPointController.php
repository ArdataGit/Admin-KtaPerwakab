<?php

namespace App\Http\Controllers;

use App\Models\TukarPoint;
use App\Models\User;
use App\Models\MasterPenukaranPoin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TukarPointController extends Controller
{
    public function index(Request $request)
    {
        $items = TukarPoint::with(['user', 'masterPenukaran'])
            ->when($request->search, function ($q) use ($request) {
                $q->whereHas('user', function ($u) use ($request) {
                    $u->where('name', 'like', "%{$request->search}%");
                });
            })
            ->latest()
            ->paginate(10);

        $users = User::orderBy('name')->get();

        $produkPenukaran = MasterPenukaranPoin::where('is_active', true)
            ->orderBy('produk')
            ->get();

        return view(
            'pages.master.tukar_point.index',
            compact('items', 'users', 'produkPenukaran')
        );
    }

    private function getSaldoPointUser($userId)
    {
        return TukarPoint::where('user_id', $userId)->sum('point');
    }


    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'master_penukaran_poin_id' => 'required|exists:master_penukaran_poin,id',
            'tanggal' => 'required|date',
        ]);

        $user = User::findOrFail($request->user_id);
        $produk = MasterPenukaranPoin::findOrFail($request->master_penukaran_poin_id);

        $poinDibutuhkan = $produk->jumlah_poin;

        // CEK SALDO DARI users.point
        if ($user->point < $poinDibutuhkan) {
            return back()->with(
                'error',
                'Poin user tidak mencukupi. Saldo tersedia: ' . $user->point . ' poin'
            );
        }

        DB::transaction(function () use ($user, $produk, $request, $poinDibutuhkan) {

            // 1. Kurangi saldo user
            $user->decrement('point', $poinDibutuhkan);

            // 2. Catat histori penukaran
            TukarPoint::create([
                'user_id' => $user->id,
                'master_penukaran_poin_id' => $produk->id,
                'point' => -$poinDibutuhkan,
                'tanggal' => $request->tanggal,
                'keterangan' => 'Tukar poin dengan ' . $produk->produk,
            ]);
        });

        return back()->with('success', 'Penukaran poin berhasil');
    }



    public function update(Request $request, TukarPoint $tukarPoint)
    {
        $request->validate([
            'master_penukaran_poin_id' => 'required|exists:master_penukaran_poin,id',
            'tanggal' => 'required|date',
        ]);

        $user = User::findOrFail($tukarPoint->user_id);
        $produkBaru = MasterPenukaranPoin::findOrFail($request->master_penukaran_poin_id);

        $poinLama = abs($tukarPoint->point); // karena disimpan minus
        $poinBaru = $produkBaru->jumlah_poin;

        DB::transaction(function () use ($user, $tukarPoint, $produkBaru, $poinLama, $poinBaru, $request) {
            // 1. Kembalikan poin lama
            $user->increment('point', $poinLama);

            // 2. Cek ulang saldo
            if ($user->point < $poinBaru) {
                throw new \Exception(
                    'Poin user tidak mencukupi untuk perubahan ini. Saldo tersedia: '
                    . $user->point . ' poin'
                );
            }

            // 3. Kurangi poin baru
            $user->decrement('point', $poinBaru);

            // 4. Update histori
            $tukarPoint->update([
                'master_penukaran_poin_id' => $produkBaru->id,
                'point' => -$poinBaru,
                'tanggal' => $request->tanggal,
                'keterangan' => 'Tukar poin dengan ' . $produkBaru->produk,
            ]);
        });

        return back()->with('success', 'Data tukar poin diperbarui');
    }



    public function destroy(TukarPoint $tukarPoint)
    {
        $user = User::findOrFail($tukarPoint->user_id);
        $poin = abs($tukarPoint->point);

        DB::transaction(function () use ($user, $tukarPoint, $poin) {
            // Kembalikan poin ke user
            $user->increment('point', $poin);

            // Hapus histori
            $tukarPoint->delete();
        });

        return back()->with('success', 'Data tukar poin dihapus dan poin dikembalikan');
    }

    //API

    public function apiHistoryByUser(Request $request, $userId)
    {
        // pastikan user ada
        $user = User::find($userId);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan',
            ], 404);
        }

        $query = TukarPoint::with('masterPenukaran')
            ->where('user_id', $userId)
            ->orderBy('tanggal', 'desc');

        // optional filter tanggal
        if ($request->filled('from')) {
            $query->whereDate('tanggal', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('tanggal', '<=', $request->to);
        }

        // pagination optional
        if ($request->has('per_page')) {
            $data = $query->paginate((int) $request->per_page);
        } else {
            $data = $query->get();
        }

        return response()->json([
            'success' => true,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'saldo_point' => $user->point,
            ],
            'data' => $data,
        ]);
    }
        
}
