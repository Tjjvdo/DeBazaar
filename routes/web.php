<?php


use App\Http\Controllers\AdvertisementController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;


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
});

Route::middleware(['auth', 'checkUserType:1'])->group(function () {
    Route::get('/newAdvertisement', [AdvertisementController::class, 'newAdvertisement'])->name('newAdvertisements');
    Route::post('/newAdvertisement', [AdvertisementController::class, 'addAdvertisement'])->name('addAdvertisements');
    Route::get('/myAdvertisements', [AdvertisementController::class, 'getMyAdvertisements'])->name('getMyAdvertisements');
    Route::get('/advertisements/{id}/View', [AdvertisementController::class, 'getSingleProduct'])->name('viewAdvertisement');
    Route::get('/advertisements/{id}/Update', [AdvertisementController::class, 'getUpdateSingleProduct'])->name('getUpdateAdvertisement');
    Route::post('/advertisements/{id}/Update', [AdvertisementController::class, 'postUpdateSingleProduct'])->name('postUpdateAdvertisement');
});

require __DIR__ . '/auth.php';