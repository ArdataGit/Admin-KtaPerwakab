<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;use Illuminate\Support\Facades\Log;
use App\Models\UserFamilyMember;

class UserController extends Controller
{
    /**
     * Update profile (tanpa foto)
     */
    
public function update(Request $request)
{
    $user = $request->user();

    DB::beginTransaction();

    try {

        /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'occupation' => 'nullable|string|max:255',
            'gender' => 'nullable|in:L,P',
            'birth_date' => 'nullable|date',

            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',

            // FAMILY VALIDATION
            'family_members' => 'nullable|array',
            'family_members.*.id' => 'nullable|exists:user_family_members,id',
            'family_members.*.relationship' => 'required_with:family_members|string|max:100',
            'family_members.*.age' => 'nullable|integer|min:0|max:120',
            'family_members.*.name_ktp' => 'required_with:family_members|string|max:255',
            'family_members.*.nickname' => 'nullable|string|max:255',
            'family_members.*.address' => 'nullable|string|max:500',
        ]);

        /*
        |--------------------------------------------------------------------------
        | NORMALIZE EMPTY VALUES
        |--------------------------------------------------------------------------
        */
        foreach (['phone', 'address', 'city', 'occupation', 'gender', 'birth_date'] as $field) {
            if (array_key_exists($field, $validated) && $validated[$field] === '') {
                $validated[$field] = null;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | HANDLE PHOTO UPLOAD
        |--------------------------------------------------------------------------
        */
        if ($request->hasFile('profile_photo')) {

            if ($user->profile_photo) {
                Storage::disk('public')->delete('profile_photos/' . $user->profile_photo);
            }

            $filename = uniqid('profile_', true) . '.' . $request->file('profile_photo')->extension();

            $request->file('profile_photo')->storeAs(
                'profile_photos',
                $filename,
                'public'
            );

            $validated['profile_photo'] = $filename;
        }

        /*
        |--------------------------------------------------------------------------
        | UPDATE USER DATA
        |--------------------------------------------------------------------------
        */
        $user->update($validated);

        

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diperbarui',
            'data' => $user->fresh()->load('familyMembers'),
        ]);

    } catch (\Throwable $e) {

        DB::rollBack();

        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan',
            'error' => $e->getMessage(),
        ], 500);
    }
}


    /**
     * Update foto profil
     */
    public function updatePhoto(Request $request)
    {
        $request->validate([
            'profile_photo' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user = $request->user();

        // ================= HAPUS FOTO LAMA =================
        if (
            $user->profile_photo &&
            Storage::exists('public/profile_photos/' . $user->profile_photo)
        ) {
            Storage::delete('public/profile_photos/' . $user->profile_photo);
        }

        // ================= SIMPAN FOTO BARU =================
        $file = $request->file('profile_photo');
        $filename = time() . '_' . uniqid() . '.' . $file->extension();

        $file->storeAs('public/profile_photos', $filename);

        // ================= SIMPAN KE DB (NAMA FILE SAJA) =================
        $user->update([
            'profile_photo' => $filename,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Foto profil berhasil diperbarui',
            'data' => $user->fresh(),
        ]);
    }
  public function deleteFamilyMember(Request $request, $id)
{
    Log::info('DELETE FAMILY HIT', [
        'route_id' => $id,
        'auth_user' => $request->user(),
        'token' => $request->bearerToken(),
        'full_url' => $request->fullUrl(),
        'method' => $request->method(),
    ]);

    try {

        $user = $request->user();

        if (!$user) {
            Log::warning('DELETE FAMILY - USER NOT AUTHENTICATED');
            return response()->json([
                'success' => false,
                'message' => 'User tidak terautentikasi',
            ], 401);
        }

        $member = UserFamilyMember::where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (!$member) {
            Log::warning('DELETE FAMILY - MEMBER NOT FOUND', [
                'requested_id' => $id,
                'user_id' => $user->id,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Data keluarga tidak ditemukan',
            ], 404);
        }

        $member->delete();

        Log::info('DELETE FAMILY SUCCESS', [
            'deleted_id' => $id,
            'user_id' => $user->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Anggota keluarga berhasil dihapus',
        ]);

    } catch (\Throwable $e) {

        Log::error('DELETE FAMILY ERROR', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan',
            'error' => $e->getMessage(),
        ], 500);
    }
}
  
  /**
 * Store new family member
 */
public function storeFamilyMember(Request $request)
{
    $user = $request->user();

    DB::beginTransaction();

    try {

        $validated = $request->validate([
            'relationship' => 'required|string|max:100',
            'birth_date'   => 'nullable|date',
            'name_ktp'     => 'required|string|max:255',
            'nickname'     => 'nullable|string|max:255',
            'address'      => 'nullable|string|max:500',
        ]);

        // Normalisasi empty string jadi null
        foreach (['birth_date', 'nickname', 'address'] as $field) {
            if (array_key_exists($field, $validated) && $validated[$field] === '') {
                $validated[$field] = null;
            }
        }

        $member = $user->familyMembers()->create($validated);

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Anggota keluarga berhasil ditambahkan',
            'data'    => $member,
        ], 201);

    } catch (\Throwable $e) {

        DB::rollBack();

        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan',
            'error'   => $e->getMessage(),
        ], 500);
    }
}
  
  public function updateFamilyMember(Request $request, $id)
{
    $user = $request->user();

    $member = UserFamilyMember::where('id', $id)
        ->where('user_id', $user->id)
        ->firstOrFail();

    $validated = $request->validate([
        'relationship' => 'required|string|max:100',
        'birth_date'   => 'nullable|date',
        'name_ktp'     => 'required|string|max:255',
        'nickname'     => 'nullable|string|max:255',
        'address'      => 'nullable|string|max:500',
    ]);

    $member->update($validated);

    return response()->json([
        'success' => true,
        'message' => 'Anggota keluarga berhasil diperbarui',
        'data'    => $member,
    ]);
}

}
