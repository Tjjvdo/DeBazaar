<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use Auth;
use Illuminate\Http\Request;

class AdvertisementController extends Controller
{
    public function newAdvertisement()
    {
        $amountOfAdvertisements = Advertisement::where('advertiser_id', Auth::user()->id)->where('inactive_at', '>', now())->orWhere('inactive_at', null)->count();

        return view('newAdvertisement', ['amountOfAdvertisements' => $amountOfAdvertisements]);

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
                "inactive_at" => now()->addUTCWeeks(2),
            ]
        );

        return redirect('/Advertisements');
    }

    public function getAdvertisements()
    {
        $advertisements = Advertisement::where('inactive_at', '>', now())->orWhere('inactive_at', null)->get();
        return view("advertisementList", ["advertisements" => $advertisements, "title" => "Advertenties"]);
    }

    public function getSingleProduct($id)
    {
        $advertisement = Advertisement::where("id", $id)->first();

        return view("viewProduct", ["advertisement" => $advertisement]);
    }

    public function getMyAdvertisements()
    {
        $advertisements = Advertisement::where("advertiser_id", Auth::user()->id)->where('inactive_at', '>', now())->orWhere('inactive_at', null)->get();

        return view("advertisementList", ["advertisements" => $advertisements, "title" => "Mijn advertenties"]);
    }

    public function getUpdateSingleProduct($id)
    {
        $advertisement = Advertisement::where("id", $id)->first();

        return view("updateAdvertisement", ["advertisement" => $advertisement]);
    }

    public function updateSingleProduct($id, Request $request)
    {
        $title = $request->input("title");
        $price = $request->input("price");
        $information = $request->input("information");

        Advertisement::where("id", $id)->update(
            [
                "title" => $title,
                "price" => $price,
                "information" => $information,
            ]
        );

        return redirect('/MyAdvertisements');
    }
}
