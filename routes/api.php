<?php

use App\Http\Controllers\Api\DonationCampaignApiController;
use App\Http\Controllers\Api\InfoDukaApiController;
use App\Http\Controllers\Api\NewsArticleController;
use App\Http\Controllers\Api\TripayApiController;
use App\Http\Controllers\Api\UmkmApiController;
use App\Http\Controllers\Api\UmkmProductApiController;
use App\Http\Controllers\Api\StrukturOrganisasiApiController;
use App\Http\Controllers\MasterPenukaranPoinController;
use App\Http\Controllers\TukarPointController;
use App\Http\Controllers\UserPointController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\MembershipFeeController;
use App\Http\Controllers\Api\UserController;

use App\Http\Controllers\Api\PublikasiApiController;

use App\Http\Controllers\Api\TripayCallbackController;

use App\Http\Controllers\Api\DonationApiController;
use App\Http\Controllers\Api\HomeBannerApiController;
use App\Http\Controllers\Api\ForgotPasswordController;
use App\Http\Controllers\Api\BisnisApiController;


Route::post('/register', [AuthApiController::class, 'register']);
Route::post('/login', [AuthApiController::class, 'login']);

Route::post('/tripay/callback', [TripayCallbackController::class, 'handle']);
// Forgot Password API (Public - tidak perlu auth)
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail']);
Route::post('/validate-reset-token', [ForgotPasswordController::class, 'validateToken']);
Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword']);

// routes/api.php
Route::middleware('auth:sanctum')->get('/me', function (Request $request) {
    return response()->json([
        'success' => true,
        'data' => $request->user()
    ]);
});

Route::middleware('auth:sanctum')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | AUTH
    |--------------------------------------------------------------------------
    */
    Route::get('/me', function (Request $request) {
        return response()->json([
            'success' => true,
            'data' => $request->user()
        ]);
    });

    Route::post('/logout', [AuthApiController::class, 'logout']);
  
  	Route::get('/home/banners', [HomeBannerApiController::class, 'index']);
  
  
    /*
    |--------------------------------------------------------------------------
    | Bisnis
    |--------------------------------------------------------------------------
    */
    Route::prefix('bisnis')->group(function () {
        Route::get('/', [BisnisApiController::class, 'index']);
        Route::get('/{slug}', [BisnisApiController::class, 'show']);
    });


    /*
    |--------------------------------------------------------------------------
    | USER
    |--------------------------------------------------------------------------
    */
    Route::post('/user/profile', [UserController::class, 'update']);
    Route::post('/user/profile/photo', [UserController::class, 'updatePhoto']);

    /*
    |--------------------------------------------------------------------------
    | MEMBERSHIP FEE
    |--------------------------------------------------------------------------
    */
    Route::prefix('membership-fee')->group(function () {
        Route::post('/', [MembershipFeeController::class, 'store']);
        Route::get('/my', [MembershipFeeController::class, 'myFees']);
        Route::get('/{id}', [MembershipFeeController::class, 'show']);
        Route::post('/{id}/upload-proof', [MembershipFeeController::class, 'uploadProof']);
        Route::post('/{id}/validate', [MembershipFeeController::class, 'validatePayment']); // admin
    });

    /*
    |--------------------------------------------------------------------------
    | DONATION
    |--------------------------------------------------------------------------
    */
    Route::post('/donations', [DonationApiController::class, 'store']);
  	Route::get('/donations/my', [DonationApiController::class, 'myDonations']);
	Route::get('/donations/{id}', [DonationApiController::class, 'show']);

    /*
    |--------------------------------------------------------------------------
    | TRIPAY
    |--------------------------------------------------------------------------
    */
    Route::get('/tripay/payment-methods', [TripayApiController::class, 'paymentMethods']);

    /*
    |--------------------------------------------------------------------------
    | NEWS & PUBLIKASI
    |--------------------------------------------------------------------------
    */
    Route::get('/news', [NewsArticleController::class, 'index']);
    Route::get('/news/{id}', [NewsArticleController::class, 'show']);

    Route::get('/publikasi', [PublikasiApiController::class, 'index']);
    Route::get('/publikasi/{id}', [PublikasiApiController::class, 'show']);

    /*
    |--------------------------------------------------------------------------
    | INFO DUKA
    |--------------------------------------------------------------------------
    */
    Route::get('/info-duka', [InfoDukaApiController::class, 'index']);
    Route::get('/info-duka/{id}', [InfoDukaApiController::class, 'show']);

    /*
    |--------------------------------------------------------------------------
    | UMKM & MARKETPLACE
    |--------------------------------------------------------------------------
    */
    Route::prefix('umkm')->group(function () {
        Route::get('/', [UmkmApiController::class, 'index']);
        Route::get('/{id}', [UmkmApiController::class, 'show']);
        Route::get('/product/{id}', [UmkmProductApiController::class, 'show']);
    });

    Route::prefix('marketplace')->group(function () {
        Route::get('/umkms', [UmkmApiController::class, 'index']);
        Route::get('/umkms/{id}', [UmkmApiController::class, 'show']);
        Route::get('/products', [UmkmProductApiController::class, 'index']);
        Route::get('/products/{id}', [UmkmProductApiController::class, 'show']);
    });

    /*
    |--------------------------------------------------------------------------
    | POINT SYSTEM
    |--------------------------------------------------------------------------
    */
    Route::get('/master-penukaran-poin', [MasterPenukaranPoinController::class, 'apiIndex']);
    Route::get('/master-penukaran-poin/{id}', [MasterPenukaranPoinController::class, 'apiDetail']);

    Route::get('/users/{userId}/tukar-point', [TukarPointController::class, 'apiHistoryByUser']);
    Route::get('/users/{userId}/point-history', [UserPointController::class, 'apiHistoryByUser']);
  
  	
    /*
    |--------------------------------------------------------------------------
    | Donation campaigns
    |--------------------------------------------------------------------------
    */
    Route::prefix('donation-campaigns')->group(function () {

            // List campaign
            Route::get('/', [DonationCampaignApiController::class, 'index']);

            // Detail campaign
            Route::get('/{id}', [DonationCampaignApiController::class, 'show']);
        });
    });
    Route::post('/donations', [DonationApiController::class, 'store']);
    Route::get('/tripay/payment-methods', [TripayApiController::class, 'paymentMethods']);

    Route::prefix('marketplace')->group(function () {

        // List UMKM (+ filter kategori)
        Route::get('/umkms', [UmkmApiController::class, 'index']);

        // Detail UMKM + produk + foto
        Route::get('/umkms/{id}', [UmkmApiController::class, 'show']);

        // semua produk (global marketplace) - HANYA APPROVED
        Route::get('/products', [UmkmProductApiController::class, 'index']);

        // detail produk - HANYA APPROVED
        Route::get('/products/{id}', [UmkmProductApiController::class, 'show']);

    });

    // Route untuk USER (butuh auth)
    Route::middleware('auth:sanctum')->group(function () {
        
        // Produk milik user (semua status)
        Route::get('/my-products', [UmkmProductApiController::class, 'myProducts']);
        
        // Tambah produk baru (otomatis pending)
        Route::post('/my-products', [UmkmProductApiController::class, 'store']);
        
        // Update produk milik user
        Route::put('/my-products/{id}', [UmkmProductApiController::class, 'update']);
        Route::post('/my-products/{id}', [UmkmProductApiController::class, 'update']); // untuk form-data
        
        // Hapus produk milik user
        Route::delete('/my-products/{id}', [UmkmProductApiController::class, 'destroy']);
        
        // Hapus foto produk
        Route::delete('/my-products/photos/{photoId}', [UmkmProductApiController::class, 'deletePhoto']);
        
    });

    Route::get('/publikasi', [PublikasiApiController::class, 'index']);
    Route::get('/publikasi/{id}', [PublikasiApiController::class, 'show']);

    Route::get('/struktur-organisasi', [StrukturOrganisasiApiController::class, 'show']);

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

Route::middleware('auth:sanctum')->post('/logout', [AuthApiController::class, 'logout']);
