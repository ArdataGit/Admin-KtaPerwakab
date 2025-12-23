<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Update profile (tanpa foto)
     */
    public function update(Request $request)
    {
        $user = $request->user();

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

            // delete old photo
            if ($user->profile_photo) {
                Storage::disk('public')->delete('profile_photos/' . $user->profile_photo);
            }

            // store new photo
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
        | UPDATE USER
        |--------------------------------------------------------------------------
        */
        $user->update($validated);

        /*
        |--------------------------------------------------------------------------
        | RESPONSE
        |--------------------------------------------------------------------------
        */
        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diperbarui',
            'data' => $user->fresh(),
        ]);
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


}
