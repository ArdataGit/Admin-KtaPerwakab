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

use App\Http\Controllers\AuthController;

Route::get('/login', [AuthController::class, 'loginPage'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    // Default route ke dashboard
    Route::get('/', function () {
        return redirect()->route('dashboard');
    });
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');


    // IURAN ANGGOTA
    Route::get('/membership-fee', [MembershipFeeController::class, 'index'])
        ->name('membership-fee.index');

    Route::get('/membership-fee/{id}', [MembershipFeeController::class, 'show'])
        ->name('membership-fee.show');

    Route::post('/membership-fee/{id}/validate', [MembershipFeeController::class, 'validatePayment'])
        ->name('membership-fee.validate');
    // Group Master Route
    Route::prefix('master')->group(function () {
      
      
        Route::get('/home-banner', [HomeBannerController::class, 'index'])
            ->name('home-banner.index');

        Route::post('/home-banner', [HomeBannerController::class, 'store'])
            ->name('home-banner.store');

        Route::post('/home-banner/{id}', [HomeBannerController::class, 'update'])
            ->name('home-banner.update');

        Route::delete('/home-banner/{id}', [HomeBannerController::class, 'destroy'])
            ->name('home-banner.destroy');

        Route::prefix('/penukaran-poin')->group(function () {
            Route::get('/', [MasterPenukaranPoinController::class, 'index'])
                ->name('penukaran-poin.index');

            Route::post('/store', [MasterPenukaranPoinController::class, 'store'])
                ->name('penukaran-poin.store');

            Route::put('/{masterPenukaranPoin}', [MasterPenukaranPoinController::class, 'update'])
                ->name('penukaran-poin.update');

            Route::delete('/{masterPenukaranPoin}', [MasterPenukaranPoinController::class, 'destroy'])
                ->name('penukaran-poin.destroy');
        });
      
        Route::prefix('bisnis')->name('bisnis.')->group(function () {
            Route::get('/', [BisnisController::class, 'index'])->name('index');
            Route::get('/create', [BisnisController::class, 'create'])->name('create');
            Route::post('/', [BisnisController::class, 'store'])->name('store');
            Route::get('/{id}', [BisnisController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [BisnisController::class, 'edit'])->name('edit');
            Route::put('/{id}', [BisnisController::class, 'update'])->name('update');
            Route::delete('/{id}', [BisnisController::class, 'destroy'])->name('destroy');


            Route::delete('/media/{id}', [BisnisController::class, 'destroyMedia'])
                ->name('media.destroy');
        });

        Route::prefix('point/tukar')->group(function () {
            Route::get('/', [TukarPointController::class, 'index'])->name('tukar-point.index');
            Route::post('/store', [TukarPointController::class, 'store'])->name('tukar-point.store');
            Route::put('/{tukarPoint}', [TukarPointController::class, 'update'])->name('tukar-point.update');
            Route::delete('/{tukarPoint}', [TukarPointController::class, 'destroy'])->name('tukar-point.destroy');
        });

        // =========================
        // DONATION CAMPAIGN (MASTER)
        // =========================

        // List campaign
        Route::get(
            '/donation-campaign',
            [DonationCampaignController::class, 'index']
        )->name('master.donation-campaign.index');

        // Form tambah campaign
        Route::get(
            '/donation-campaign/create',
            [DonationCampaignController::class, 'create']
        )->name('master.donation-campaign.create');

        // Simpan campaign baru
        Route::post(
            '/donation-campaign',
            [DonationCampaignController::class, 'store']
        )->name('master.donation-campaign.store');

        // Form edit campaign
        Route::get(
            '/donation-campaign/{donationCampaign}/edit',
            [DonationCampaignController::class, 'edit']
        )->name('master.donation-campaign.edit');

        // Update campaign
        Route::put(
            '/donation-campaign/{donationCampaign}',
            [DonationCampaignController::class, 'update']
        )->name('master.donation-campaign.update');

        // Hapus campaign
        Route::delete(
            '/donation-campaign/{donationCampaign}',
            [DonationCampaignController::class, 'destroy']
        )->name('master.donation-campaign.destroy');



        Route::get('/publikasi', [PublikasiController::class, 'index'])->name('publikasi.index');

        Route::get('/publikasi/create', [PublikasiController::class, 'create'])->name('publikasi.create');

        Route::post('/publikasi', [PublikasiController::class, 'store'])->name('publikasi.store');

        // Delete individual photo/video (HARUS SEBELUM route {id})
        Route::delete('/publikasi/photo/{photoId}', [PublikasiController::class, 'deletePhoto'])->name('publikasi.photo.delete');
        Route::delete('/publikasi/video/{videoId}', [PublikasiController::class, 'deleteVideo'])->name('publikasi.video.delete');

        Route::get('/publikasi/{id}', [PublikasiController::class, 'show'])->name('publikasi.show');

        Route::get('/publikasi/{id}/edit', [PublikasiController::class, 'edit'])->name('publikasi.edit');

        Route::put('/publikasi/{id}', [PublikasiController::class, 'update'])->name('publikasi.update');

        Route::delete('/publikasi/{id}', [PublikasiController::class, 'destroy'])->name('publikasi.destroy');

        // LIST
        Route::get('/info-duka', [InfoDukaController::class, 'index'])
            ->name('info-duka.index');

        // CREATE
        Route::get('/info-duka/create', [InfoDukaController::class, 'create'])
            ->name('info-duka.create');

        // STORE
        Route::post('/info-duka', [InfoDukaController::class, 'store'])
            ->name('info-duka.store');

        // SHOW (opsional, kalau admin perlu preview)
        Route::get('/info-duka/{id}', [InfoDukaController::class, 'show'])
            ->name('info-duka.show');

        // EDIT
        Route::get('/info-duka/{id}/edit', [InfoDukaController::class, 'edit'])
            ->name('info-duka.edit');

        Route::put('/info-duka/{infoDuka}', [InfoDukaController::class, 'update'])
            ->name('info-duka.update');

        // DELETE
        Route::delete('/info-duka/{id}', [InfoDukaController::class, 'destroy'])
            ->name('info-duka.destroy');
      
      
        // LIST
        Route::get(
            '/donation-transactions',
            [DonationTransactionController::class, 'index']
        )->name('master.donation-transaction.index');

        // DETAIL
        Route::get(
            '/donation-transactions/{donation}',
            [DonationTransactionController::class, 'show']
        )->name('master.donation-transaction.show');


        // POSISI
        Route::get('/posisi', [PositionController::class, 'index'])->name('posisi.index');
        Route::post('/posisi', [PositionController::class, 'store'])->name('posisi.store');
        Route::put('/posisi/{position}', [PositionController::class, 'update'])->name('posisi.update');
        Route::delete('/posisi/{position}', [PositionController::class, 'destroy'])->name('posisi.destroy');

        // DIVISI
        Route::get('/divisi', [DivisionController::class, 'index'])->name('divisi.index');
        Route::post('/divisi', [DivisionController::class, 'store'])->name('divisi.store');
        Route::put('/divisi/{division}', [DivisionController::class, 'update'])->name('divisi.update');
        Route::delete('/divisi/{division}', [DivisionController::class, 'destroy'])->name('divisi.destroy');

        // STRUKTUR ORGANISASI
        Route::get('/struktur-organisasi', [StrukturOrganisasiController::class, 'index'])->name('struktur-organisasi.index');
        Route::get('/struktur-organisasi/create', [StrukturOrganisasiController::class, 'create'])->name('struktur-organisasi.create');
        Route::post('/struktur-organisasi', [StrukturOrganisasiController::class, 'store'])->name('struktur-organisasi.store');
        Route::get('/struktur-organisasi/edit', [StrukturOrganisasiController::class, 'edit'])->name('struktur-organisasi.edit');
        Route::put('/struktur-organisasi', [StrukturOrganisasiController::class, 'update'])->name('struktur-organisasi.update');
        Route::delete('/struktur-organisasi', [StrukturOrganisasiController::class, 'destroy'])->name('struktur-organisasi.destroy');

        // Routes untuk Point Kategori (CRUD manual)
        Route::get('/point-kategoris', [PointKategoriController::class, 'index'])->name('point-kategoris.index');
        Route::post('/point-kategoris', [PointKategoriController::class, 'store'])->name('point-kategoris.store');
        Route::get('/point-kategoris/{pointKategori}', [PointKategoriController::class, 'show'])->name('point-kategoris.show');
        Route::put('/point-kategoris/{pointKategori}', [PointKategoriController::class, 'update'])->name('point-kategoris.update');
        Route::delete('/point-kategoris/{pointKategori}', [PointKategoriController::class, 'destroy'])->name('point-kategoris.destroy');

        // Routes untuk User Point (CRUD manual)
        Route::get('/user-points', [UserPointController::class, 'index'])->name('user-points.index');
        Route::post('/user-points', [UserPointController::class, 'store'])->name('user-points.store');
        Route::get('/user-points/{userPoint}', [UserPointController::class, 'show'])->name('user-points.show');
        Route::put('/user-points/{userPoint}', [UserPointController::class, 'update'])->name('user-points.update');
        Route::delete('/user-points/{userPoint}', [UserPointController::class, 'destroy'])->name('user-points.destroy');

        // Opsional: Route tambahan untuk filter user point berdasarkan user_id
        //Route::get('/user-points/by-user/{userId}', [UserPointController::class, 'index'])->name('user-points.by-user');

        // USER
        Route::get('/user', [UserController::class, 'index'])->name('user.index');
        Route::get('/user/anggota', [UserController::class, 'anggota'])->name('user.anggota');

        Route::post('/users/{user}/add-point', [UserPointController::class, 'addPoint'])->name('user.add-point');

        Route::post('/points/add-by-category', [UserPointController::class, 'storeMass'])
            ->name('points.add-by-category');


        Route::post('/user', [UserController::class, 'store'])->name('user.store');
        Route::put('/user/{id}', [UserController::class, 'update'])->name('user.update');
        Route::delete('/user/{id}', [UserController::class, 'destroy'])->name('user.destroy');

        Route::prefix('news')->group(function () {


            Route::get('/', [NewsArticleController::class, 'index'])->name('news.index');
            Route::get('/create', [NewsArticleController::class, 'create'])->name('news.create');
            Route::post('/', [NewsArticleController::class, 'store'])->name('news.store');

            Route::get('/{id}', [NewsArticleController::class, 'show'])->name('news.show');
            Route::get('/{id}/edit', [NewsArticleController::class, 'edit'])->name('news.edit');
            Route::put('/{id}', [NewsArticleController::class, 'update'])->name('news.update');

            Route::delete('/{id}', [NewsArticleController::class, 'destroy'])->name('news.destroy');

        });
        Route::prefix('umkm')->group(function () {

            // UMKM
            Route::get('/', [UmkmController::class, 'index'])->name('umkm.index');
            Route::get('/create', [UmkmController::class, 'create'])->name('umkm.create');
            Route::post('/', [UmkmController::class, 'store'])->name('umkm.store');
            Route::get('/{umkm}/edit', [UmkmController::class, 'edit'])->name('umkm.edit');
            Route::put('/{umkm}', [UmkmController::class, 'update'])->name('umkm.update');
            Route::delete('/{umkm}', [UmkmController::class, 'destroy'])->name('umkm.destroy');

            // PRODUK UMKM
            Route::get('/{umkm}/products', [UmkmProductController::class, 'index'])
                ->name('umkm.products.index');

            Route::get('/{umkm}/products/create', [UmkmProductController::class, 'create'])
                ->name('umkm.products.create');

            Route::post('/{umkm}/products', [UmkmProductController::class, 'store'])
                ->name('umkm.products.store');

            Route::get('/{umkm}/products/{product}/edit', [UmkmProductController::class, 'edit'])
                ->name('umkm.products.edit');

            Route::put('/{umkm}/products/{product}', [UmkmProductController::class, 'update'])
                ->name('umkm.products.update');

            Route::delete('/{umkm}/products/{product}', [UmkmProductController::class, 'destroy'])
                ->name('umkm.products.destroy');

            // Approve & Reject Product
            Route::post('/{umkm}/products/{product}/approve', [UmkmProductController::class, 'approve'])
                ->name('umkm.products.approve');

            Route::post('/{umkm}/products/{product}/reject', [UmkmProductController::class, 'reject'])
                ->name('umkm.products.reject');

        });

        // FOTO PRODUK UMKM
        Route::post('/umkm-product/{product}/photos', [UmkmProductPhotoController::class, 'store'])
            ->name('umkm.product.photos.store');

        Route::delete('/umkm-product-photo/{photo}', [UmkmProductPhotoController::class, 'destroy'])
            ->name('umkm.product-photos.destroy');

    });

});

