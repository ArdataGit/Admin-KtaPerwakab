<?php
// app/Http/Controllers/Api/UmkmApiController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Umkm;
use Illuminate\Http\Request;

class UmkmApiController extends Controller
{
    public function index(Request $request)
    {
        $query = Umkm::with('products.photos')
            ->latest();

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        return response()->json([
            'success' => true,
            'data' => $query->paginate(10),
        ]);
    }

    public function show($id)
    {
        $umkm = Umkm::with('products.photos')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $umkm,
        ]);
    }
}
