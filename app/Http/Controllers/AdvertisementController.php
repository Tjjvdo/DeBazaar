<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use App\Models\AdvertisementRelated;
use App\Models\Bid;
use App\Models\Renting;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

use function Pest\Laravel\get;

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
            $inactive_at = now()->addUTCMonths(2);
        } else {
            $isRentable = false;
            if ($this->getAmountOfBidAdvertisements() > 3) {
                return Redirect::route('getMyAdvertisements');
            }
            $inactive_at = now()->addUTCWeek();
        }

        $advertisement = Advertisement::create(
            [
                "title" => $title,
                "price" => $price,
                "information" => $information,
                "created_at" => now(),
                "advertiser_id" => Auth::user()->id,
                "is_rentable" => $isRentable,
                "inactive_at" => $inactive_at,
            ]
        );

        if (!$isRentable) {
            Bid::create([
                "advertisement_id" => $advertisement->id,
                "bid_amount" => $price,
            ]);
        }

        return Redirect::route('getUpdateAdvertisement', $advertisement->id);
    }

    public function getAdvertisements()
    {
        $advertisements = Advertisement::where('inactive_at', '>', now())->get();

        return view("advertisementList", ["advertisements" => $advertisements, "title" => "Advertisements"]);
    }

    public function getMyAdvertisements()
    {
        $advertisements = Advertisement::where("advertiser_id", Auth::user()->id)->where('inactive_at', '>', now())->get();

        return view("advertisementList", ["advertisements" => $advertisements, "title" => "My advertisements"]);
    }

    public function getSingleProduct($id)
    {
        $advertisement = Advertisement::where("id", $id)->first();

        if ($advertisement->is_rentable) {
            $today = date('Y-m-d');
            $tomorrow = date('Y-m-d', strtotime('+1 day'));
            $maxDate = $advertisement->inactive_at->format('Y-m-d');
            $bidding = null;
        } else {
            $bidding = Bid::where('advertisement_id', $id)->first();
            $today = null;
            $tomorrow = null;
            $maxDate = null;
        }

        $relatedAdvertisements = AdvertisementRelated::where("advertisement_id", $id)->with('relatedAdvertisement')->get();

        return view("viewProduct", ["advertisement" => $advertisement, "today" => $today, "tomorrow" => $tomorrow, "maxDate" => $maxDate, "bidding" => $bidding, 'amountOfBids' => $this->getAmountOfBids(), 'relatedAdvertisements' => $relatedAdvertisements]);
    }

    public function getUpdateSingleProduct($id)
    {
        $advertisement = Advertisement::where("id", $id)->first();

        $relatedAdvertisements = AdvertisementRelated::where("advertisement_id", $id)->with('relatedAdvertisement')->get();

        $existingRelatedIds = $relatedAdvertisements->pluck('related_advertisement_id')->toArray();
        $existingRelatedIds[] = $advertisement->id;

        $advertisements = Advertisement::where("advertiser_id", Auth::user()->id)->whereNotIn('id', $existingRelatedIds)->get();

        return view("updateAdvertisement", ["advertisement" => $advertisement, "relatedAdvertisements" => $relatedAdvertisements, "advertisements" => $advertisements]);
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

    public function addRelationToProduct($id, Request $request)
    {
        $relationId = $request->input("relationId");

        if ($relationId) {
            AdvertisementRelated::create(
                [
                    'advertisement_id' => $id,
                    'related_advertisement_id' => $relationId,
                ]
            );
        }

        return Redirect::route('getUpdateAdvertisement', $id);
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

    public function getAmountOfBidAdvertisements()
    {
        return Advertisement::where('advertiser_id', Auth::user()->id)->where('inactive_at', '>', now())->where('is_rentable', 0)->count();
    }

    public function getAmountOfRentAdvertisements()
    {
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

        return Redirect::route('rentCalendar');
    }

    public function rentalCalendar()
    {
        $advertisements = Advertisement::where('advertiser_id', Auth::user()->id)->with('rentings')->get();

        $rentalEvents = [];

        foreach ($advertisements as $advertisement) {
            foreach ($advertisement->rentings as $renting) {
                $endDate = new \DateTime($renting->end_date->format('Y-m-d'));
                $endDate->add(new \DateInterval('P1D'));

                $rentalEvents[] = [
                    'title' => $advertisement->title,
                    'start' => $renting->start_date->format('Y-m-d'),
                    'end' => $endDate->format('Y-m-d'),
                    'url' => route('viewAdvertisement', $advertisement->id),
                ];
            }

            if ($advertisement->inactive_at) {
                $rentalEvents[] = [
                    'title' => __('advertisements.advertisement_ending') . ' ' . $advertisement->title,
                    'start' => $advertisement->inactive_at->format('Y-m-d'),
                    'end' => $advertisement->inactive_at->format('Y-m-d'),
                    'url' => route('viewAdvertisement', $advertisement->id),
                    'allDay' => true,
                    'backgroundColor' => 'lightgray',
                    'borderColor' => 'gray',
                    'textColor' => 'black',
                ];
            }
        }

        return view('rentalCalendar', compact('rentalEvents'));
    }

    public function rentCalendar()
    {
        $rentings = Renting::where('renter_id', Auth::user()->id)->get();

        $rentalEvents = [];

        foreach ($rentings as $renting) {
            $endDate = new \DateTime($renting->end_date->format('Y-m-d'));
            $endDate->add(new \DateInterval('P1D'));

            $rentalEvents[] = [
                'title' => $renting->advertisement->title,
                'start' => $renting->start_date->format('Y-m-d'),
                'end' => $endDate->format('Y-m-d'),
                'url' => route('viewAdvertisement', $renting->advertisement_id),
            ];
        }

        return view('rentalCalendar', compact('rentalEvents'));
    }
}
