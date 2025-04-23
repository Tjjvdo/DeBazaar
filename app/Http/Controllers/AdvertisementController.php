<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use App\Models\AdvertisementRelated;
use App\Models\AdvertiserReview;
use App\Models\Bid;
use App\Models\Favorite;
use App\Models\ProductReview;
use App\Models\Renting;
use App\Models\User;
use App\Models\WearSetting;
use App\View\Components\Advertisment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use League\Csv\Reader;
use League\Csv\Exception as CsvException;

use function Pest\Laravel\get;

class AdvertisementController extends Controller
{
    public function newAdvertisement()
    {
        $amountOfBidAdvertisements = $this->getAmountOfBidAdvertisements();

        $amountOfRentAdvertisements = $this->getAmountOfRentAdvertisements();

        return view('newAdvertisement', compact('amountOfBidAdvertisements', 'amountOfRentAdvertisements'));
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

    public function uploadAdvertisementsCSV(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|mimes:csv,txt',
        ]);

        if ($request->hasFile('csv_file')) {
            $file = $request->file('csv_file');

            try {
                $csv = Reader::createFromPath($file->getPathname(), 'r');
                $csv->setHeaderOffset(0);
                $records = $csv->getRecords();
                $uploadedCount = 0;
                $errorMessages = [];

                foreach ($records as $record) {
                    if (!isset($record['title']) || !isset($record['price']) || !isset($record['information']) || !isset($record['is_rentable'])) {
                        $errorMessages[] = __('messages.csv_invalid_row_structure');
                        continue;
                    }

                    $title = trim($record['title']);
                    $price = filter_var($record['price'], FILTER_VALIDATE_FLOAT);
                    $information = trim($record['information']);
                    $isRentable = filter_var($record['is_rentable'], FILTER_VALIDATE_BOOLEAN);

                    $dataToValidate = [
                        'title' => $title,
                        'price' => $price,
                        'information' => $information,
                    ];

                    $rules = [
                        'title' => 'required|string|max:255',
                        'price' => 'required|numeric|min:0.01',
                        'information' => 'required|string',
                    ];

                    $rowValidator = Validator::make($dataToValidate, $rules);

                    if ($rowValidator->fails()) {
                        foreach ($rowValidator->errors()->all() as $error) {
                            $errorMessages[] = __('messages.csv_validation_error', ['title' => $title, 'error' => $error]);
                        }
                        continue;
                    }

                    $amountOfBidAdvertisements = $this->getAmountOfBidAdvertisements();
                    $amountOfRentAdvertisements = $this->getAmountOfRentAdvertisements();

                    if (($isRentable && $amountOfRentAdvertisements < 4) || (!$isRentable && $amountOfBidAdvertisements < 4)) {
                        $inactive_at = $isRentable ? now()->addUTCMonths(2) : now()->addUTCWeek();

                        $advertisement = Advertisement::create([
                            "title" => $title,
                            "price" => $price,
                            "information" => $information,
                            "created_at" => now(),
                            "advertiser_id" => Auth::user()->id,
                            "is_rentable" => $isRentable,
                            "inactive_at" => $inactive_at,
                        ]);

                        if (!$isRentable) {
                            Bid::create([
                                "advertisement_id" => $advertisement->id,
                                "bid_amount" => $price,
                            ]);
                        }
                        $uploadedCount++;
                    } else {
                        $errorMessages[] = __('messages.max_advertisements_reached_csv', ['title' => $title]);
                    }
                }

                if ($uploadedCount == iterator_count($records)) {
                    return Redirect::route('getMyAdvertisements')
                    ->with('success', __('messages.csv_upload_success_all', ['count' => $uploadedCount]));
                } elseif ($uploadedCount > 0) {
                    return Redirect::route('getMyAdvertisements')
                    ->with('warning', __('messages.csv_upload_partial', 
                    ['uploaded' => $uploadedCount, 'total' => iterator_count($records)]))
                    ->withErrors($errorMessages);
                } else {
                    return Redirect::back()->withErrors($errorMessages);
                }
            } catch (CsvException $e) {
                return Redirect::back()->withErrors(['csv_file' => __('messages.csv_processing_error')]);
            }
        }
    }

    public function getAdvertisements(Request $request)
    {
        $query = Advertisement::where('inactive_at', '>', now());

        if ($request->has('title') && !empty($request->title)) {
            $query->where('title', 'like', '%' . $request->title . '%');
        }
    
        if ($request->has('price_min') && is_numeric($request->price_min)) {
            $query->where('price', '>=', $request->price_min);
        }
    
        if ($request->has('price_max') && is_numeric($request->price_max)) {
            $query->where('price', '<=', $request->price_max);
        }
    
        if ($request->has('is_rentable') && in_array($request->is_rentable, ['0', '1'])) {
            $query->where('is_rentable', $request->is_rentable);
        }

        $advertisements = $query->paginate(3)->withQueryString();

        return view("advertisementList", ["advertisements" => $advertisements, "title" => "Advertisements"]);
    }

    public function getMyAdvertisements()
    {
        $advertisements = Advertisement::where("advertiser_id", Auth::user()->id)->where('inactive_at', '>', now())->paginate(3);

        return view("advertisementList", ["advertisements" => $advertisements, "title" => "My advertisements"]);
    }

    public function getSingleProduct($id)
    {
        $advertisement = Advertisement::where("id", $id)->with('advertiser')->first();

        if ($advertisement->is_rentable) {
            $today = Carbon::now();
            $tomorrow = date('Y-m-d', strtotime('+1 day'));
            $maxDate = $advertisement->inactive_at->format('Y-m-d');
            $bidding = null;
            $reviews = ProductReview::where('advertisement_id', $id)->with('user')->paginate(3);
            $renting = Renting::where('advertisement_id', $id)->where('renter_id', Auth::user()->id)->where('return_date', null)->first();
        } else {
            $bidding = Bid::where('advertisement_id', $id)->first();
            $today = null;
            $tomorrow = null;
            $maxDate = null;
            $reviews = null;
            $renting = null;
        }

        $relatedAdvertisements = AdvertisementRelated::where("advertisement_id", $id)->with('relatedAdvertisement')->get();

        $favorited = Favorite::where('advertisement_id', $advertisement->id)->first();

        $isFavorite = $favorited ? true : false;
        $amountOfBids = $this->getAmountOfBids();

        return view("viewProduct", compact('advertisement', 'reviews', 'today', 'tomorrow', 'maxDate', 'bidding', 'amountOfBids', 'relatedAdvertisements', 'isFavorite', 'renting'));
    }

    public function getUpdateSingleProduct($id)
    {
        $advertisement = Advertisement::where("id", $id)->first();

        $relatedAdvertisements = AdvertisementRelated::where('advertisement_id', $id)->with('relatedAdvertisement')->get();

        $existingRelatedIds = $relatedAdvertisements->pluck('related_advertisement_id')->toArray();
        $existingRelatedIds[] = $advertisement->id;

        $advertisements = Advertisement::where('advertiser_id', Auth::user()->id)->whereNotIn('id', $existingRelatedIds)->get();

        $wearsettings = WearSetting::where('advertisement_id', $id)->first();

        return view("updateAdvertisement", compact('advertisement', 'relatedAdvertisements', 'advertisements', 'wearsettings'));
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

    public function getMyPurchases()
    {
        $biddedAdvertisements = Bid::where('bidder_id', Auth::user()->id)->get();

        $biddedAdvertisementIds = $biddedAdvertisements->pluck('advertisement_id')->toArray();

        $advertisements = Advertisement::where('inactive_at', '<', now())->whereIn('id', $biddedAdvertisementIds)->paginate(3);

        return view("advertisementList", ["advertisements" => $advertisements, "title" => "PurchaseHistory"]);
    }

    public function getMyFavorites()
    {
        $favorites = Favorite::where('user_id', Auth::user()->id)->get();

        $advertisementIds = $favorites->pluck('advertisement_id')->toArray();

        $advertisements = Advertisement::whereIn('id', $advertisementIds)->paginate(3);

        return view("advertisementList", ["advertisements" => $advertisements, "title" => "Favorites"]);
    }

    public function addMyFavorite($id)
    {
        Favorite::create(
            [
                'advertisement_id' => $id,
                'user_id' => Auth::user()->id,
            ]
        );

        return Redirect::route('viewAdvertisement', $id);
    }

    public function removeMyFavorite($id)
    {
        Favorite::where('advertisement_id', $id)->where('user_id', Auth::user()->id)->delete();

        return Redirect::route('viewAdvertisement', $id);
    }

    public function reviewAdvertisement($id, Request $request)
    {
        ProductReview::create(
            [
                'user_id' => Auth::user()->id,
                'advertisement_id' => $id,
                'review' => $request->input("review"),
            ]
        );

        return Redirect::route('viewAdvertisement', $id);
    }

    public function getAdvertiserReviews($id)
    {
        $advertiser = User::where('id', $id)->first();

        $reviews = AdvertiserReview::where('advertiser_id', $id)->with('advertiser')->paginate(3);

        $amountOfAdvertisements = Advertisement::where('advertiser_id', $id)->count();

        return view('advertiserReviews', compact('advertiser', 'reviews', 'amountOfAdvertisements'));
    }

    public function postAdvertiserReview($id, Request $request)
    {
        AdvertiserReview::create(
            [
                'reviewer_id' => Auth::user()->id,
                'advertiser_id' => $id,
                'review' => $request->input('review'),
            ]
        );

        return Redirect::route('getAdvertiserReviews', $id);
    }

    public function addWearSettings($id, Request $request)
    {
        WearSetting::create(
            [
                'advertisement_id' => $id,
                'investment_amount' => $request->input('investmentAmount'),
                'days_durable' => $request->input('durability'),
            ]
        );

        return Redirect::route('getUpdateAdvertisement', $id);
    }

    public function returnRental($id, Request $request)
    {
        $renting = Renting::where('advertisement_id', $id)->where('renter_id', Auth::user()->id)->where('return_date', null)->first();

        $image = $request->file('img_url');
        $filename = 'return_' . time() . '_' . $image->getClientOriginalName();
        $path = $image->storeAs('returns', $filename, 'public');
        $renting->image_path = $path;

        $renting->return_date = Carbon::now();
        $renting->save();

        $daysUsed = Carbon::parse($renting->start_date)->diffInDays(Carbon::now()) + 1;

        $wearsettings = WearSetting::where('advertisement_id', $id)->first();

        if ($wearsettings && $wearsettings->days_durable > 0 && isset($wearsettings->investment_amount)) {
            $usedPart = $daysUsed / $wearsettings->days_durable;
            $cost = round($usedPart * $wearsettings->investment_amount, 2);
        } else {
            $cost = 0;
        }

        return redirect()->back()->with('warning', __('advertisements.wear_costs') . ': ' . $cost);
    }
}
