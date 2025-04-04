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
        $amountOfBidAdvertisements = $this->getAmountOfBidAdvertisements();

        $amountOfRentAdvertisements = $this->getAmountOfRentAdvertisements();

        return view('newAdvertisement', ['amountOfBidAdvertisements' => $amountOfBidAdvertisements, 'amountOfRentAdvertisements' => $amountOfRentAdvertisements]);
    }

    public function addAdvertisement(Request $request)
    {
        $title = $request->input("title");
        $price = $request->input("price");
        $information = $request->input("information");
        $isRentable = $request->input("rentable");

        if ($isRentable) {
            $isRentable = true;
            if ($this->getAmountOfRentAdvertisements() > 3) {
                return Redirect::route('getMyAdvertisements');
            }
        } else {
            $isRentable = false;
            if ($this->getAmountOfBidAdvertisements() > 3) {
                return Redirect::route('getMyAdvertisements');
            }
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
        $advertisements = Advertisement::where('inactive_at', '>', now())->get();

        return view("advertisementList", ["advertisements" => $advertisements, "title" => "Advertisements"]);
    }

    public function getSingleProduct($id)
    {
        $advertisement = Advertisement::where("id", $id)->first();

        if ($advertisement->is_rentable) {
            $renting = Renting::where('advertisement_id', $id)->first();
            $today = date('Y-m-d');
            $maxDate = date('Y-m-d', strtotime('+1 year'));
            $bidding = null;
        } else {
            $bidding = Bid::where('advertisement_id', $id)->first();
            $renting = null;
            $today = null;
            $maxDate = null;
        }

        return view("viewProduct", ["advertisement" => $advertisement, "renting" => $renting, "today" => $today, "maxDate" => $maxDate, "bidding" => $bidding, 'amountOfBids' => $this->getAmountOfBids()]);
    }

    public function getMyAdvertisements()
    {
        $advertisements = Advertisement::where("advertiser_id", Auth::user()->id)->where('inactive_at', '>', now())->get();

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

        if ($this->getAmountOfBids() < 4) {
            $bid = Advertisement::where('advertisement_id', $id)->where('inactive_at', '>', now())->first();

            if (!$bid) {
                Bid::where('advertisement_id', $id)->update(
                    [
                        "bidder_id" => Auth::user()->id,
                        "bid_amount" => $bidAmount,
                    ]
                );
            }
        }

        return Redirect::route('viewAdvertisement', $id);
    }

    public function getAmountOfBids()
    {
        $activeBidAdvertisements = Advertisement::where('inactive_at', '>', now())->where('is_rentable', 0)->pluck('id');

        $amountOfBiddings = Bid::where('bidder_id', Auth::user()->id)
            ->whereIn('advertisement_id', $activeBidAdvertisements)
            ->count();

        return $amountOfBiddings;
    }

    public function getAmountOfBidAdvertisements(){
        return Advertisement::where('advertiser_id', Auth::user()->id)->where('inactive_at', '>', now())->where('is_rentable', 0)->count();
    }
    
    public function getAmountOfRentAdvertisements(){
        return Advertisement::where('advertiser_id', Auth::user()->id)->where('inactive_at', '>', now())->where('is_rentable', 1)->count();
    }

    public function rentProduct($id, Request $request)
    {
        $validatedData = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        $extraData = [
            'advertisement_id' => $id,
            'renter_id' => Auth::user()->id,
        ];

        $validatedData = array_merge($validatedData, $extraData);

        Renting::create($validatedData);

        return Redirect::route('viewAdvertisement', $id);
    }
}
