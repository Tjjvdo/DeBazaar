<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AdvertisementApiController;

Route::middleware(['api.key'])->get('/active-ads/{companyEmail}', [AdvertisementApiController::class, 'getActiveAdvertisements']);