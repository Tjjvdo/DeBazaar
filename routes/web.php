<?php

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Http\Controllers\AdvertisementController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\LandingPageController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\SetLocale;

Route::middleware([SetLocale::class])->group(function () {
    Route::get('/', function () {
        return view('welcome');
    });

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware(['auth', 'verified'])->name('dashboard');

    Route::middleware('auth')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
        Route::get('/advertisements', [AdvertisementController::class, 'getAdvertisements'])->name('advertisements');
        Route::get('/advertisements/{id}/View', [AdvertisementController::class, 'getSingleProduct'])->name('viewAdvertisement');
        Route::post('/advertisements/{id}/View/bid', [AdvertisementController::class, 'bidOnProduct'])->name('bidOnProduct');
        Route::post('/advertisements/{id}/View/rent', [AdvertisementController::class, 'rentProduct'])->name('rentProduct');
        Route::get('/rentSchedule', [AdvertisementController::class, 'rentCalendar'])->name('rentCalendar');
        Route::get('/landingpage/{slug}', [LandingPageController::class, 'show'])->name('landingpage.show');
        Route::get('/advertisements/purchaseHistory', [AdvertisementController::class, 'getMyPurchases'])->name('myPurchases');
        Route::get('/advertisements/favorites', [AdvertisementController::class, 'getMyFavorites'])->name('myFavorites');
        Route::post('/advertisements/{id}/View/favorite', [AdvertisementController::class, 'addMyFavorite'])->name('addMyFavorite');
        Route::delete('/advertisements/{id}/View/favorite', [AdvertisementController::class, 'removeMyFavorite'])->name('removeMyFavorite');
    });

    Route::middleware(['auth', 'checkUserType:3'])->group(function () {
        Route::get('/business-contracts', [ContractController::class, 'index'])->name('business-contracts');
        Route::get('/contracts/download/{user_id}', [ContractController::class, 'download'])->name('contracts.download');
        Route::post('/contracts/upload', [ContractController::class, 'upload'])->name('contracts.upload');
    });

    Route::middleware(['auth', 'checkUserType:2', 'checkContractStatus:pending'])->group(function () {
        Route::get('/my-contract', [ContractController::class, 'showAdvertiserContract'])->name('contracts.advertiser');
        Route::post('/my-contract/respond', [ContractController::class, 'respondToContract'])->name('contracts.respond');
    });

    Route::middleware('auth')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
        Route::get('/advertisements', [AdvertisementController::class, 'getAdvertisements'])->name('advertisements');
        Route::get('/advertisements/{id}/View', [AdvertisementController::class, 'getSingleProduct'])->name('viewAdvertisement');
        Route::post('/advertisements/{id}/View/bid', [AdvertisementController::class, 'bidOnProduct'])->name('bidOnProduct');
        Route::post('/advertisements/{id}/View/rent', [AdvertisementController::class, 'rentProduct'])->name('rentProduct');
        Route::get('/rentSchedule', [AdvertisementController::class, 'rentCalendar'])->name('rentCalendar');
        Route::get('/advertisements/purchaseHistory', [AdvertisementController::class, 'getMyPurchases'])->name('myPurchases');
        Route::get('/advertisements/favorites', [AdvertisementController::class, 'getMyFavorites'])->name('myFavorites');
        Route::post('/advertisements/{id}/View/favorite', [AdvertisementController::class, 'addMyFavorite'])->name('addMyFavorite');
        Route::delete('/advertisements/{id}/View/favorite', [AdvertisementController::class, 'removeMyFavorite'])->name('removeMyFavorite');
        Route::post('/advertisements/{id}/View/review', [AdvertisementController::class, 'reviewAdvertisement'])->name('reviewAdvertisement');
        Route::get('/advertisers/{id}/reviews', [AdvertisementController::class, 'getAdvertiserReviews'])->name('getAdvertiserReviews');
        Route::post('/advertisers/{id}/reviews', [AdvertisementController::class, 'postAdvertiserReview'])->name('postAdvertiserReview');
    });
    
    Route::middleware(['auth', 'checkUserType:2', 'checkContractStatus:accepted'])->group(function () {
        Route::get('/my-landingpage', [LandingPageController::class, 'myLandingpage'])->name('my-landingpage');
        Route::post('/landingpage/save', [LandingPageController::class, 'save'])->name('landingpage.save');
    });

    Route::middleware(['auth', 'checkUserTypes:1,2', 'checkContractStatus:accepted'])->group(function () {
        Route::get('/newAdvertisement', [AdvertisementController::class, 'newAdvertisement'])->name('newAdvertisements');
        Route::post('/newAdvertisement', [AdvertisementController::class, 'addAdvertisement'])->name('addAdvertisements');
        Route::get('/myAdvertisements', [AdvertisementController::class, 'getMyAdvertisements'])->name('getMyAdvertisements');
        Route::get('/advertisements/{id}/Update', [AdvertisementController::class, 'getUpdateSingleProduct'])->name('getUpdateAdvertisement');
        Route::post('/advertisements/{id}/Update', [AdvertisementController::class, 'postUpdateSingleProduct'])->name('postUpdateAdvertisement');
        Route::get('/rentOutSchedule', [AdvertisementController::class, 'rentalCalendar'])->name('rentalCalendar');
        Route::post('/advertisements/{id}/UpdateRelations', [AdvertisementController::class, 'addRelationToProduct'])->name('postUpdateAdvertisementRelation');
        Route::post('/newAdvertisement/uploadCSV', [AdvertisementController::class, 'uploadAdvertisementsCSV'])->name('uploadAdvertisementsCSV');
    });
});

Route::get('/api-test/{company_name}/{api_key}', function ($company_name, $api_key) {
    return view('api.api-test', compact('company_name', 'api_key'));
})->name('api-test');

Route::get('switch-language/{language}', function ($language) {
    $availableLanguages = ['en', 'nl'];
    
    if (in_array($language, $availableLanguages)) {
        session()->put('locale', $language);
    }

    return redirect()->back();
})->name('switch-language');

require __DIR__ . '/auth.php';