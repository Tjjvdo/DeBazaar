<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use App\Models\Bid;
use App\Models\Renting;
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
        $title = $request->input("title");
        $price = $request->input("price");
        $information = $request->input("information");
        $isRentable = $request->input("rentable");

        if ($isRentable) {
            $isRentable = true;
        } else {
            $isRentable = false;
        }

        $advertisement = Advertisement::create(
            [
                "title" => $title,
                "price" => $price,
                "information" => $information,
                "created_at" => now(),
                "advertiser_id" => Auth::user()->id,
                "is_rentable" => $isRentable,
                "inactive_at" => now()->addUTCWeeks(2),
            ]
        );

        if (!$isRentable) {
            Bid::create([
                "advertisement_id" => $advertisement->id,
                "bid_amount" => $price,
            ]);
        }

        return Redirect::route('getMyAdvertisements');
    }

    public function getAdvertisements()
    {
        $advertisements = Advertisement::where('inactive_at', '>', now())->orWhere('inactive_at', null)->get();

        return view("advertisementList", ["advertisements" => $advertisements, "title" => "Advertisements"]);
    }

    public function getSingleProduct($id)
    {
        $advertisement = Advertisement::where("id", $id)->first();

        if ($advertisement->is_rentable) {
            $renting = Renting::where('advertisement_id', $id)->first();
            $bidding = null;
        } else {
            $bidding = Bid::where('advertisement_id', $id)->first();
            $renting = null;
        }

        return view("viewProduct", ["advertisement" => $advertisement, "renting" => $renting, "bidding" => $bidding]);
    }

    public function getMyAdvertisements()
    {
        $advertisements = Advertisement::where("advertiser_id", Auth::user()->id)->where('inactive_at', '>', now())->orWhere('inactive_at', null)->get();

        return view("advertisementList", ["advertisements" => $advertisements, "title" => "My advertisements"]);
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

    public function bidOnProduct($id, Request $request)
    {
        $bidAmount = $request->input("bid");

        Bid::where('advertisement_id', $id)->update(
            [
                "bidder_id" => Auth::user()->id,
                "bid_amount" => $bidAmount,
            ]
        );

        return Redirect::route('viewAdvertisement', $id);
    }
}
