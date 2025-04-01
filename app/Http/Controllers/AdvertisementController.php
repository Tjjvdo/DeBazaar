<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use Illuminate\Http\Request;

class AdvertisementController extends Controller
{
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
