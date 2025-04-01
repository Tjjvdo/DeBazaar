<?php

use App\Http\Controllers\AdvertisementController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/NewAdvertisement', function () {
    return view('newAdvertisement');
});

Route::post('/NewAdvertisement', [AdvertisementController::class, 'addAdvertisement']);

Route::get('/Advertisement', [AdvertisementController::class,'getAdvertisements']);