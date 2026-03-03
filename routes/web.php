<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DivisionController;
use App\Http\Controllers\InfoDukaController;
use App\Http\Controllers\MasterPenukaranPoinController;
use App\Http\Controllers\NewsArticleController;
use App\Http\Controllers\PointKategoriController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\TukarPointController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PublikasiController;
use App\Http\Controllers\UserPointController;
use App\Http\Controllers\StrukturOrganisasiController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MembershipFeeController;
use App\Http\Controllers\UmkmController;
use App\Http\Controllers\UmkmProductController;
use App\Http\Controllers\UmkmProductPhotoController;
use App\Http\Controllers\DonationCampaignController;
use App\Http\Controllers\DonationTransactionController;
use App\Http\Controllers\HomeBannerController;
use App\Http\Controllers\BisnisController;
use App\Http\Controllers\OrganizationHistoryController;
use App\Http\Controllers\KtaTemplateController;



use App\Http\Controllers\AuthController;

Route::get('/login', [AuthController::class, 'loginPage'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    // Default route ke dashboard - Semua role bisa akses
    Route::get('/', function () {
        return redirect()->route('dashboard');
    });
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
  
    Route::get('organization/history', [OrganizationHistoryController::class, 'index'])
            ->name('organization.history.index');

        Route::post('organization/history', [OrganizationHistoryController::class, 'store'])
            ->name('organization.history.store');
	
    // ========================================
    // ROUTES UNTUK BENDAHARA, ADMIN & SUPERADMIN
    // ========================================
    Route::middleware(['role:superadmin,admin,bendahara'])->group(function () {
        // IURAN ANGGOTA
        Route::get('/membership-fee', [MembershipFeeController::class, 'index'])
            ->name('membership-fee.index');
        Route::get('/membership-fee/{id}', [MembershipFeeController::class, 'show'])
            ->name('membership-fee.show');
        Route::post('/membership-fee/{id}/validate', [MembershipFeeController::class, 'validatePayment'])
            ->name('membership-fee.validate');

        // DONATION CAMPAIGN
        Route::get('/donation-campaign', [DonationCampaignController::class, 'index'])
            ->name('master.donation-campaign.index');
        Route::get('/donation-campaign/create', [DonationCampaignController::class, 'create'])
            ->name('master.donation-campaign.create');
        Route::post('/donation-campaign', [DonationCampaignController::class, 'store'])
            ->name('master.donation-campaign.store');
        Route::get('/donation-campaign/{donationCampaign}/edit', [DonationCampaignController::class, 'edit'])
            ->name('master.donation-campaign.edit');
        Route::put('/donation-campaign/{donationCampaign}', [DonationCampaignController::class, 'update'])
            ->name('master.donation-campaign.update');
        Route::delete('/donation-campaign/{donationCampaign}', [DonationCampaignController::class, 'destroy'])
            ->name('master.donation-campaign.destroy');
    });

    // ========================================
    // ROUTES UNTUK PENGURUS, ADMIN & SUPERADMIN
    // ========================================
    Route::middleware(['role:superadmin,admin,pengurus'])->group(function () {
      
    // List
          Route::get('/kta-templates', 
              [KtaTemplateController::class, 'index']
          )->name('kta-templates.index');

          // Form Create
          Route::get('/kta-templates/create', 
              [KtaTemplateController::class, 'create']
          )->name('kta-templates.create');

          // Store
          Route::post('/kta-templates', 
              [KtaTemplateController::class, 'store']
          )->name('kta-templates.store');

          // Form Edit
          Route::get('/kta-templates/{id}/edit', 
              [KtaTemplateController::class, 'edit']
          )->name('kta-templates.edit');

          // Update
          Route::put('/kta-templates/{id}', 
              [KtaTemplateController::class, 'update']
          )->name('kta-templates.update');

          // Delete
          Route::delete('/kta-templates/{id}', 
              [KtaTemplateController::class, 'destroy']
          )->name('kta-templates.destroy');
      
          Route::post('/kta-templates/{id}/activate', 
              [KtaTemplateController::class, 'activate']
          )->name('kta-templates.activate');
      
      
        // NEWS/ARTIKEL
        Route::prefix('news')->group(function () {
            Route::get('/', [NewsArticleController::class, 'index'])->name('news.index');
            Route::get('/create', [NewsArticleController::class, 'create'])->name('news.create');
            Route::post('/', [NewsArticleController::class, 'store'])->name('news.store');
            Route::get('/{id}', [NewsArticleController::class, 'show'])->name('news.show');
            Route::get('/{id}/edit', [NewsArticleController::class, 'edit'])->name('news.edit');
            Route::put('/{id}', [NewsArticleController::class, 'update'])->name('news.update');
            Route::delete('/{id}', [NewsArticleController::class, 'destroy'])->name('news.destroy');
        });

        // PUBLIKASI
        Route::prefix('publikasi')->group(function () {
            Route::get('/', [PublikasiController::class, 'index'])->name('publikasi.index');
            Route::get('/create', [PublikasiController::class, 'create'])->name('publikasi.create');
            Route::post('/', [PublikasiController::class, 'store'])->name('publikasi.store');
            Route::get('/{id}', [PublikasiController::class, 'show'])->name('publikasi.show');
            Route::get('/{id}/edit', [PublikasiController::class, 'edit'])->name('publikasi.edit');
            Route::put('/{id}', [PublikasiController::class, 'update'])->name('publikasi.update');
            Route::delete('/{id}', [PublikasiController::class, 'destroy'])->name('publikasi.destroy');
        });

        // BISNIS (KARYA DAN BISNIS)
        Route::prefix('bisnis')->name('bisnis.')->group(function () {
            Route::get('/', [BisnisController::class, 'index'])->name('index');
            Route::get('/create', [BisnisController::class, 'create'])->name('create');
            Route::post('/', [BisnisController::class, 'store'])->name('store');
            Route::get('/{id}', [BisnisController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [BisnisController::class, 'edit'])->name('edit');
            Route::put('/{id}', [BisnisController::class, 'update'])->name('update');
            Route::delete('/{id}', [BisnisController::class, 'destroy'])->name('destroy');
            Route::delete('/media/{id}', [BisnisController::class, 'destroyMedia'])->name('media.destroy');
        });

        // INFO DUKA
        Route::prefix('info-duka')->group(function () {
            Route::get('/', [InfoDukaController::class, 'index'])->name('info-duka.index');
            Route::post('/', [InfoDukaController::class, 'store'])->name('info-duka.store');
            Route::put('/{infoDuka}', [InfoDukaController::class, 'update'])->name('info-duka.update');
            Route::delete('/{infoDuka}', [InfoDukaController::class, 'destroy'])->name('info-duka.destroy');
        });

        // STRUKTUR ORGANISASI
        Route::prefix('struktur-organisasi')->group(function () {
            Route::get('/', [StrukturOrganisasiController::class, 'index'])->name('struktur-organisasi.index');
            Route::get('/create', [StrukturOrganisasiController::class, 'create'])->name('struktur-organisasi.create');
            Route::post('/', [StrukturOrganisasiController::class, 'store'])->name('struktur-organisasi.store');
            Route::get('/{id}/edit', [StrukturOrganisasiController::class, 'edit'])->name('struktur-organisasi.edit');
            Route::put('/{id}', [StrukturOrganisasiController::class, 'update'])->name('struktur-organisasi.update');
            Route::delete('/{id}', [StrukturOrganisasiController::class, 'destroy'])->name('struktur-organisasi.destroy');
        });

        // HOME BANNER
        Route::get('/home-banner', [HomeBannerController::class, 'index'])->name('home-banner.index');
        Route::post('/home-banner', [HomeBannerController::class, 'store'])->name('home-banner.store');
        Route::post('/home-banner/{id}', [HomeBannerController::class, 'update'])->name('home-banner.update');
        Route::delete('/home-banner/{id}', [HomeBannerController::class, 'destroy'])->name('home-banner.destroy');

        // UMKM
        Route::get('/umkm-product', [UmkmProductController::class, 'indexAll'])->name('umkm-product.index');
        Route::prefix('umkm')->group(function () {
            Route::get('/', [UmkmController::class, 'index'])->name('umkm.index');
            Route::get('/create', [UmkmController::class, 'create'])->name('umkm.create');
            Route::post('/', [UmkmController::class, 'store'])->name('umkm.store');
            Route::get('/{umkm}/edit', [UmkmController::class, 'edit'])->name('umkm.edit');
            Route::put('/{umkm}', [UmkmController::class, 'update'])->name('umkm.update');
            Route::delete('/{umkm}', [UmkmController::class, 'destroy'])->name('umkm.destroy');

            // PRODUK UMKM
            Route::get('/{umkm}/products', [UmkmProductController::class, 'index'])->name('umkm.products.index');
            Route::get('/{umkm}/products/create', [UmkmProductController::class, 'create'])->name('umkm.products.create');
            Route::post('/{umkm}/products', [UmkmProductController::class, 'store'])->name('umkm.products.store');
            Route::get('/{umkm}/products/{product}/edit', [UmkmProductController::class, 'edit'])->name('umkm.products.edit');
            Route::put('/{umkm}/products/{product}', [UmkmProductController::class, 'update'])->name('umkm.products.update');
            Route::delete('/{umkm}/products/{product}', [UmkmProductController::class, 'destroy'])->name('umkm.products.destroy');
            Route::post('/{umkm}/products/{product}/approve', [UmkmProductController::class, 'approve'])->name('umkm.products.approve');
            Route::post('/{umkm}/products/{product}/reject', [UmkmProductController::class, 'reject'])->name('umkm.products.reject');
        });

        // FOTO PRODUK UMKM
        Route::post('/umkm-product/{product}/photos', [UmkmProductPhotoController::class, 'store'])->name('umkm.product.photos.store');
        Route::delete('/umkm-product-photo/{photo}', [UmkmProductPhotoController::class, 'destroy'])->name('umkm.product-photos.destroy');
    });

    // ========================================
    // ROUTES UNTUK ADMIN & SUPERADMIN SAJA
    // ========================================
    Route::middleware(['role:superadmin,admin'])->group(function () {
        // USER ANGGOTA
        Route::get('/user/anggota', [UserController::class, 'anggota'])->name('user.anggota');

        // USER MANAGEMENT
        Route::get('/user', [UserController::class, 'index'])->name('user.index');
        Route::post('/user', [UserController::class, 'store'])->name('user.store');
        Route::put('/user/{id}', [UserController::class, 'update'])->name('user.update');
        Route::delete('/user/{id}', [UserController::class, 'destroy'])->name('user.destroy');

        // POINT KATEGORI
        Route::get('/point-kategoris', [PointKategoriController::class, 'index'])->name('point-kategoris.index');
        Route::post('/point-kategoris', [PointKategoriController::class, 'store'])->name('point-kategoris.store');
        Route::get('/point-kategoris/{pointKategori}', [PointKategoriController::class, 'show'])->name('point-kategoris.show');
        Route::put('/point-kategoris/{pointKategori}', [PointKategoriController::class, 'update'])->name('point-kategoris.update');
        Route::delete('/point-kategoris/{pointKategori}', [PointKategoriController::class, 'destroy'])->name('point-kategoris.destroy');

        // USER POINT
        Route::post('/user-point/store', [UserPointController::class, 'apiStore'])->name('user-point.store');
        Route::post('/points/add-by-category', [UserPointController::class, 'storeMass'])
              ->name('points.add-by-category');

        // PENUKARAN POIN
        Route::prefix('/penukaran-poin')->group(function () {
            Route::get('/', [MasterPenukaranPoinController::class, 'index'])->name('penukaran-poin.index');
            Route::post('/store', [MasterPenukaranPoinController::class, 'store'])->name('penukaran-poin.store');
            Route::put('/{masterPenukaranPoin}', [MasterPenukaranPoinController::class, 'update'])->name('penukaran-poin.update');
            Route::delete('/{masterPenukaranPoin}', [MasterPenukaranPoinController::class, 'destroy'])->name('penukaran-poin.destroy');
        });

        // TUKAR POINT
        Route::prefix('point/tukar')->group(function () {
            Route::get('/', [TukarPointController::class, 'index'])->name('tukar-point.index');
            Route::post('/store', [TukarPointController::class, 'store'])->name('tukar-point.store');
            Route::put('/{tukarPoint}', [TukarPointController::class, 'update'])->name('tukar-point.update');
            Route::delete('/{tukarPoint}', [TukarPointController::class, 'destroy'])->name('tukar-point.destroy');

            Route::patch('/{tukarPoint}/approve', 
                [TukarPointController::class, 'approve'])
                ->name('tukar-point.approve');

            Route::patch('/{tukarPoint}/reject', 
                [TukarPointController::class, 'reject'])
                ->name('tukar-point.reject');
        });

        // DIVISION
        Route::get('/division', [DivisionController::class, 'index'])->name('division.index');
        Route::post('/division', [DivisionController::class, 'store'])->name('division.store');
        Route::put('/division/{division}', [DivisionController::class, 'update'])->name('division.update');
        Route::delete('/division/{division}', [DivisionController::class, 'destroy'])->name('division.destroy');

        // POSITION
        Route::get('/position', [PositionController::class, 'index'])->name('position.index');
        Route::post('/position', [PositionController::class, 'store'])->name('position.store');
        Route::put('/position/{position}', [PositionController::class, 'update'])->name('position.update');
        Route::delete('/position/{position}', [PositionController::class, 'destroy'])->name('position.destroy');
    });
});