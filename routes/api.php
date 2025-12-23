<?php

use App\Http\Controllers\Api\InfoDukaApiController;
use App\Http\Controllers\Api\NewsArticleController;
use App\Http\Controllers\Api\UmkmApiController;
use App\Http\Controllers\Api\UmkmProductApiController;
use App\Http\Controllers\MasterPenukaranPoinController;
use App\Http\Controllers\TukarPointController;
use App\Http\Controllers\UserPointController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\MembershipFeeController;
use App\Http\Controllers\Api\UserController;

use App\Http\Controllers\Api\PublikasiApiController;

Route::post('/register', [AuthApiController::class, 'register']);
Route::post('/login', [AuthApiController::class, 'login']);

// routes/api.php
Route::middleware('auth:sanctum')->get('/me', function (Request $request) {
    return response()->json([
        'success' => true,
        'data' => $request->user()
    ]);
});

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/membership-fee', [MembershipFeeController::class, 'store']);

    Route::post('/membership-fee/{id}/upload-proof', [MembershipFeeController::class, 'uploadProof']);

    Route::get('/membership-fee/my', [MembershipFeeController::class, 'myFees']);


    Route::get('/membership-fee/{id}', [MembershipFeeController::class, 'show']);

    // ADMIN
    Route::post('/membership-fee/{id}/validate', [MembershipFeeController::class, 'validatePayment']);


    Route::post('/user/profile', [UserController::class, 'update']);

    Route::post('/user/profile/photo', [UserController::class, 'updatePhoto']);

    Route::get('/news', [NewsArticleController::class, 'index']);
    Route::get('/news/{id}', [NewsArticleController::class, 'show']);

    Route::prefix('umkm')->group(function () {
        Route::get('/', [UmkmApiController::class, 'index']);
        Route::get('/{id}', [UmkmApiController::class, 'show']);

        Route::get('/product/{id}', [UmkmProductApiController::class, 'show']);
    });

    Route::prefix('marketplace')->group(function () {

        // List UMKM (+ filter kategori)
        Route::get('/umkms', [UmkmApiController::class, 'index']);

        // Detail UMKM + produk + foto
        Route::get('/umkms/{id}', [UmkmApiController::class, 'show']);

        // semua produk (global marketplace)
        Route::get('/products', [UmkmProductApiController::class, 'index']);

        // detail produk
        Route::get('/products/{id}', [UmkmProductApiController::class, 'show']);

    });

    Route::get('/publikasi', [PublikasiApiController::class, 'index']);
    Route::get('/publikasi/{id}', [PublikasiApiController::class, 'show']);


    Route::get('/info-duka', [InfoDukaApiController::class, 'index']);
    Route::get('/info-duka/{id}', [InfoDukaApiController::class, 'show']);

    Route::get(
        '/master-penukaran-poin',
        [MasterPenukaranPoinController::class, 'apiIndex']
    );

    Route::get(
        '/master-penukaran-poin/{id}',
        [MasterPenukaranPoinController::class, 'apiDetail']
    );

    Route::get(
        '/users/{userId}/tukar-point',
        [TukarPointController::class, 'apiHistoryByUser']
    );
    Route::get(
        '/users/{userId}/point-history',
        [UserPointController::class, 'apiHistoryByUser']
    );
});

Route::middleware('auth:sanctum')->post('/logout', [AuthApiController::class, 'logout']);