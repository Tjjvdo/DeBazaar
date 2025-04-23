<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Advertisement;
use App\Models\User;
use Carbon\Carbon;

class AdvertisementApiController extends Controller
{
    public function getActiveAdvertisements($companyName)
    {
        $user = User::where('name', $companyName)->where('user_type', 2)->first();

        if (!$user) {
            return response()->json(['error' => 'Company not found or not a business advertiser'], 404);
        }

        $ads = Advertisement::where('advertiser_id', $user->id)
                            ->where('inactive_at', '>', Carbon::now())
                            ->get();

        return response()->json($ads);
    }
}