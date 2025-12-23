<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UserController extends Controller
{

    private function hitungUsia($birthDate)
    {
        if (!$birthDate)
            return null;
        return \Carbon\Carbon::parse($birthDate)->age;
    }
    private function hitungExpired($joinDate)
    {
        if (!$joinDate)
            return null;

        return \Carbon\Carbon::parse($joinDate)->addYear();
    }


    /**
     * Display users page (Blade).
     */
    public function index()
    {
        $users = User::orderBy('id', 'desc')->paginate(10);

        $users->transform(function ($user) {
            $user->profile_photo_url = $user->profile_photo
                ? asset('storage/profile_photos/' . $user->profile_photo)
                : null;
            return $user;
        });

        return view('pages.master.user', compact('users'));
    }

    public function anggota(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | BASE QUERY
        |--------------------------------------------------------------------------
        | Ambil user role anggota & publik
        */
        $query = User::query()
            ->whereIn('role', ['anggota', 'publik'])
            ->with([
                'userPoints.pointKategori',
                'userPoints.createdBy',
            ]);

        /*
        |--------------------------------------------------------------------------
        | FILTER: ROLE (anggota / publik)
        |--------------------------------------------------------------------------
        | ?role=anggota
        | ?role=publik
        */
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        /*
        |--------------------------------------------------------------------------
        | SEARCH: NAMA / EMAIL / TELEPON
        |--------------------------------------------------------------------------
        */
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        /*
        |--------------------------------------------------------------------------
        | FILTER: USIA
        |--------------------------------------------------------------------------
        */
        if ($request->filled('usia_min')) {
            $query->where('age', '>=', (int) $request->usia_min);
        }

        if ($request->filled('usia_max')) {
            $query->where('age', '<=', (int) $request->usia_max);
        }

        /*
        |--------------------------------------------------------------------------
        | FILTER: KOTA / DOMISILI
        |--------------------------------------------------------------------------
        */
        if ($request->filled('city')) {
            $query->where('city', 'like', '%' . $request->city . '%');
        }

        /*
        |--------------------------------------------------------------------------
        | FILTER: STATUS AKUN
        |--------------------------------------------------------------------------
        */
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        /*
        |--------------------------------------------------------------------------
        | FILTER: TAHUN JOIN
        |--------------------------------------------------------------------------
        */
        if ($request->filled('tahun_join')) {
            $query->whereYear('join_date', $request->tahun_join);
        }

        /*
        |--------------------------------------------------------------------------
        | PAGINATION
        |--------------------------------------------------------------------------
        */
        $users = $query
            ->orderByDesc('id')
            ->paginate(10)
            ->appends($request->query());

        /*
        |--------------------------------------------------------------------------
        | TRANSFORM: PROFILE PHOTO URL
        |--------------------------------------------------------------------------
        */
        $users->getCollection()->transform(function ($user) {
            $user->profile_photo_url = $user->profile_photo
                ? asset('storage/profile_photos/' . $user->profile_photo)
                : null;

            return $user;
        });

        return view('pages.master.user_anggota', compact('users'));
    }



    /**
     * Store new user.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:255',
            'gender' => 'nullable|string|max:50',
            'birth_date' => 'nullable|date',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'occupation' => 'nullable|string|max:255',
            'join_date' => 'nullable|date',
            'status' => 'required|string|in:aktif,nonaktif',
            'role' => 'required|in:superadmin,admin,pengurus,anggota,bendahara,publik',
            'password' => 'required|string|min:6',
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        if (!Storage::exists('public/profile_photos')) {
            Storage::makeDirectory('public/profile_photos');
        }

        if ($request->hasFile('profile_photo')) {
            $file = $request->file('profile_photo');
            $filename = time() . '_' . uniqid() . '.' . $file->extension();
            $file->storeAs('public/profile_photos', $filename);
            $validated['profile_photo'] = $filename;
        }

        // Hash password
        $validated['password'] = Hash::make($validated['password']);

        // Inisialisasi point untuk anggota
        if ($validated['role'] === 'anggota') {
            $validated['point'] = 0;
        }

        // Age
        $validated['age'] = $this->hitungUsia($validated['birth_date'] ?? null);

        // JOIN DATE otomatis kalau kosong
        $joinDate = $validated['join_date'] ?? now()->toDateString();
        $validated['join_date'] = $joinDate;

        // EXPIRED otomatis 1 tahun hanya untuk anggota
        if ($validated['role'] === 'anggota') {
            $validated['expired_at'] = $this->hitungExpired($joinDate);
        }

        User::create($validated);

        return redirect()->back()->with('success', 'User berhasil ditambahkan');
    }


    /**
     * Update user.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => ['sometimes', 'email', Rule::unique('users')->ignore($user->id)],
            'phone' => 'nullable|string|max:255',
            'gender' => 'nullable|string|max:50',
            'birth_date' => 'nullable|date',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'occupation' => 'nullable|string|max:255',
            'join_date' => 'nullable|date',
            'status' => 'sometimes|string|in:aktif,nonaktif',
            'role' => 'sometimes|in:superadmin,admin,pengurus,anggota,bendahara,publik',
            'password' => 'nullable|string|min:6',
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        if ($request->hasFile('profile_photo')) {

            if ($user->profile_photo && Storage::exists('public/profile_photos/' . $user->profile_photo)) {
                Storage::delete('public/profile_photos/' . $user->profile_photo);
            }

            $file = $request->file('profile_photo');
            $filename = time() . '_' . uniqid() . '.' . $file->extension();
            $file->storeAs('public/profile_photos', $filename);
            $validated['profile_photo'] = $filename;
        }

        // Inisialisasi point jika ganti role ke anggota dan point belum ada
        $newRole = $validated['role'] ?? $user->role;
        if ($newRole === 'anggota' && !isset($user->point)) {
            $validated['point'] = 0;
        }

        // update age
        if (!empty($validated['birth_date'])) {
            $validated['age'] = $this->hitungUsia($validated['birth_date']);
        }

        // update expired_at (anggota saja)
        if ($newRole === 'anggota') {

            $joinDate = $validated['join_date'] ?? $user->join_date ?? now();

            $validated['expired_at'] = $this->hitungExpired($joinDate);
        }

        $newStatus = $validated['status'] ?? $user->status;
        $newRole = $validated['role'] ?? $user->role;

        if (
            $newRole === 'anggota' &&
            $newStatus === 'aktif' &&
            empty($user->expired_at)
        ) {
            $joinDate = $validated['join_date'] ?? $user->join_date ?? now();
            $validated['expired_at'] = $this->hitungExpired($joinDate);
        }

        $user->update($validated);

        return redirect()->back()->with('success', 'User berhasil diperbarui');
    }



    /**
     * Delete user.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // \Log::info("REQUEST DELETE USER ID $id");

        if ($user->profile_photo && Storage::exists('public/profile_photos/' . $user->profile_photo)) {
            Storage::delete('public/profile_photos/' . $user->profile_photo);
            \Log::info("FOTO USER DIHAPUS", ['file' => $user->profile_photo]);
        }

        $user->delete();

        // \Log::info("USER DELETE BERHASIL", ['user_id' => $id]);

        return redirect()->back()->with('success', 'User berhasil dihapus');
    }
}