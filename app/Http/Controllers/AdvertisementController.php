<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class AdvertisementController extends Controller
{
    public function newAdvertisement()
    {
        $amountOfAdvertisements = Advertisement::where('advertiser_id', Auth::user()->id)->where('inactive_at', '>', now())->orWhere('inactive_at', null)->count();

        return view('newAdvertisement', ['amountOfAdvertisements' => $amountOfAdvertisements]);
    }

    public function addAdvertisement(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required',
            'price' => 'required|numeric',
            'information' => 'required',
        ]);
        
        $validatedData['advertiser_id'] = Auth::user()->id;
        $validatedData['inactive_at'] = now()->addUTCWeeks(2);

        Advertisement::create($validatedData);

        return Redirect::route('getMyAdvertisements');
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

    public function postUpdateSingleProduct($id, Request $request)
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
        return Redirect::route('getMyAdvertisements');
    }
}
