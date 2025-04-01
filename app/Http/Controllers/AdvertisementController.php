<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use Auth;
use Illuminate\Http\Request;

class AdvertisementController extends Controller
{
    public function newAdvertisement()
    {
        $amountOfAdvertisements = Advertisement::where('advertiser_id', Auth::user()->id)->where('inactive_at', '<', now())->orWhere('inactive_at', null)->count();

        if ($amountOfAdvertisements < 4) {
            return view('newAdvertisement');
        } else {
            return redirect('/dashboard');
        }
    }

    public function addAdvertisement(Request $request)
    {
        $title = $request->input("title");
        $price = $request->input("price");
        $information = $request->input("information");

        Advertisement::create(
            [
                "title" => $title,
                "price" => $price,
                "information" => $information,
                "created_at" => now(),
                "advertiser_id" => Auth::user()->id,
            ]
        );

        return redirect('/Advertisement');
    }

    public function getAdvertisements()
    {
        $advertisements = Advertisement::all();
        return view("advertisementList", ["advertisements" => $advertisements]);
    }
}
