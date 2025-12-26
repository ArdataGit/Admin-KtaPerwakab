<?php

namespace App\Http\Controllers;

use App\Models\UserPoint;
use App\Models\PointKategori;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class UserPointController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = UserPoint::with(['pointKategori', 'user', 'createdBy'])
            ->when($request->search, function ($q) use ($request) {
                $q->whereHas('pointKategori', function ($sub) use ($request) {
                    $sub->where('name', 'like', '%' . $request->search . '%');
                })->orWhereHas('user', function ($sub) use ($request) {
                    $sub->where('name', 'like', '%' . $request->search . '%');
                });
            })
            ->when($request->user_id, function ($q) use ($request) {
                $q->where('id_user', $request->user_id);
            });

        $userPoints = $query->paginate(15)->withQueryString();

        return view('user-points.index', compact('userPoints'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'id_category' => ['required', 'exists:point_kategori,id'],
            'id_user' => ['required', 'exists:users,id'],
            'created_by' => ['required', 'exists:users,id'],
        ]);

        $userPoint = UserPoint::create($validated);

        // Update total point user
        $user = User::find($validated['id_user']);
        $user->increment('point', $userPoint->pointKategori->point);

        return redirect()->route('user-points.index')->with('success', 'User point added successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(UserPoint $userPoint)
    {
        $userPoint->load(['pointKategori', 'user', 'createdBy']);

        return redirect()->route('user-points.index'); // Atau buat view detail jika diperlukan
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UserPoint $userPoint): RedirectResponse
    {
        $oldPoint = $userPoint->pointKategori->point; // Point lama

        $validated = $request->validate([
            'id_category' => ['sometimes', 'exists:point_kategori,id'],
            'id_user' => ['sometimes', 'exists:users,id'],
            'created_by' => ['sometimes', 'exists:users,id'],
        ]);

        $userPoint->update($validated);

        // Update total point user jika kategori berubah
        if (isset($validated['id_category'])) {
            $newPoint = $userPoint->fresh()->pointKategori->point;
            $diff = $newPoint - $oldPoint;
            $userPoint->user->increment('point', $diff);
        }

        return redirect()->route('user-points.index')->with('success', 'User point updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserPoint $userPoint): RedirectResponse
    {
        $pointToSubtract = $userPoint->pointKategori->point;

        $userPoint->delete();

        // Kurangi total point user
        $userPoint->user->decrement('point', $pointToSubtract);

        return back()->with('success', 'User point deleted successfully');
    }

    /**
     * Tambah point untuk user tertentu (untuk integrasi di User modal).
     * Route: POST /users/{user}/add-point
     */
    public function addPoint(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'id_category' => ['required', 'exists:point_kategori,id'],
        ]);

        $userPoint = UserPoint::create([
            'id_category' => $validated['id_category'],
            'id_user' => $user->id,
            'created_by' => auth()->id() ?? 1, // Asumsi admin login, atau default 1
        ]);

        // Update total point user
        $user->increment('point', $userPoint->pointKategori->point);

        return back()->with('point_success', 'Kegiatan berhasil ditambahkan: ' . $userPoint->pointKategori->name . ' (' . $userPoint->pointKategori->point . ' points)');
    }

    public function storeMass(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'point_kategori_id' => ['required', 'integer', 'exists:point_kategori,id'], // Match form name
            'users' => ['required', 'array', 'min:1'], // Match form name (users[])
            'users.*' => ['integer', 'exists:users,id'], // Validate array items
        ]);

        $kategori = PointKategori::findOrFail($validated['point_kategori_id']);
        $point = $kategori->point;
        $createdBy = auth()->id(); // Assume authenticated user; adjust if needed

        DB::transaction(function () use ($validated, $kategori, $point, $createdBy) {
            // Prepare insert data for UserPoint history
            $insertData = [];
            foreach ($validated['users'] as $userId) {
                $insertData[] = [
                    'id_category' => $kategori->id,
                    'id_user' => $userId,
                    'created_by' => $createdBy,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            UserPoint::insert($insertData);

            // Increment points on users in one query
            User::whereIn('id', $validated['users'])
                ->increment('point', $point);
        });

        return redirect()
            ->route('point-kategoris.index') // Redirect back to category index for consistency
            ->with('success', 'Point kategori berhasil ditambahkan ke user terpilih');
    }



    //API

    public function apiHistoryByUser(Request $request, $userId)
    {
        $user = User::find($userId);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan',
            ], 404);
        }

        $query = UserPoint::with(['pointKategori', 'createdBy'])
            ->where('id_user', $userId)
            ->orderBy('created_at', 'desc');

        // Optional filter kategori
        if ($request->filled('category_id')) {
            $query->where('id_category', $request->category_id);
        }

        // Optional filter tanggal
        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        // Pagination optional
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