<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('advertisements.view_advertisement') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg relative">

                    <div class="absolute top-4 right-4">
                        {!! QrCode::size(175)->generate(Request::url()) !!}
                    </div>

                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <x-advertisement class="p-4 border rounded-lg shadow-md dark:border-gray-700">
                            <x-slot:title>{{ $advertisement->title }}</x-slot:title>
                            <x-slot:price>{{ number_format($advertisement->price, 2, ',', '.') }}</x-slot:price>
                            <x-slot:information>{{ $advertisement->information }}</x-slot:information>
                            <x-slot:created_at>{{ $advertisement->created_at->format('d-m-Y H:i') }}</x-slot:created_at>
                            <x-slot:id>{{ $advertisement->id }}</x-slot:id>
                        </x-advertisement>

                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                            {{ __('advertisements.sold_by') }}:
                            <a href="{{ route('getAdvertiserReviews', $advertisement->advertiser_id) }}" class="font-semibold text-indigo-600 dark:text-indigo-400 hover:underline">
                                {{ $advertisement->advertiser->name }}
                            </a>
                        </p>
                    </div>

                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        @if ($advertisement->is_rentable)
                        <form action="{{ route('rentProduct', $advertisement->id) }}" method="POST" class="space-y-6">
                            @csrf
                            <div>
                                <label for="start_date" class="block text-lg font-medium text-gray-700 dark:text-gray-300">{{ __('advertisements.begin_date') }}</label>
                                <div class="mt-2">
                                    <input type="date" id="start_date" min="{{$today}}" max="{{$maxDate}}" name="start_date"
                                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-lg border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 p-2">
                                </div>
                            </div>

                            <div>
                                <label for="end_date" class="block text-lg font-medium text-gray-700 dark:text-gray-300">{{ __('advertisements.end_date') }}</label>
                                <div class="mt-2">
                                    <input type="date" id="end_date" min="{{$tomorrow}}" max="{{$maxDate}}" name="end_date"
                                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-lg border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 p-2">
                                </div>
                            </div>

                            <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">{{ __('advertisements.rent') }}</button>
                        </form>

                        <div class="mt-4">
                            @if ($isFavorite)
                            <form action="{{ route('removeMyFavorite', $advertisement->id) }}" method="post" class="space-y-6">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    {{ __('advertisements.remove_from_favorites') }}
                                </button>
                            </form>
                            @else
                            <form action="{{ route('addMyFavorite', $advertisement->id) }}" method="post" class="space-y-6">
                                @csrf
                                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    {{ __('advertisements.add_to_favorites') }}
                                </button>
                            </form>
                            @endif
                        </div>
                        @else
                        @if ($bidding->bidder_id)
                        <p class="block text-lg font-medium text-gray-700 dark:text-gray-300">{{ __('advertisements.highest_bidder') }} {{ $bidding->bidder->name }}</p>
                        <p class="block text-lg font-medium text-gray-700 dark:text-gray-300">{{ __('advertisements.highest_bid') }} {{ number_format($bidding->bid_amount, 2, ',', '.') }}</p>
                        @else
                        <p class="block text-lg font-medium text-gray-700 dark:text-gray-300">Startbod: €{{ number_format($bidding->bid_amount, 2, ',', '.') }}</p>
                        @endif
                        @if ($amountOfBids < 4)
                            <form action="{{ route('bidOnProduct', $advertisement->id) }}" method="post" class="space-y-6">
                            @csrf
                            <div class="mt-4">
                                @if ($bidding->bidder_id)
                                <label for="bid" class="block text-lg font-medium text-gray-700 dark:text-gray-300">{{ __('advertisements.your_bid') }} ({{ __('advertisements.minimum') }} €{{ number_format($bidding->bid_amount + 1, 2, ',', '.') }}):</label>
                                <div class="mt-2">
                                    <input type="number" id="bid" name="bid" min="{{ $bidding->bid_amount + 1 }}" step="0.01"
                                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-lg border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 p-2"
                                        placeholder="{{ __('advertisements.enter_your_bid') }}">
                                </div>
                                @else
                                <label for="bid" class="block text-lg font-medium text-gray-700 dark:text-gray-300">{{ __('advertisements.your_bid') }} ({{ __('advertisements.minimum') }} €{{ number_format($bidding->bid_amount, 2, ',', '.') }}):</label>
                                <div class="mt-2">
                                    <input type="number" id="bid" name="bid" min="{{ $bidding->bid_amount }}" step="0.01"
                                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-lg border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 p-2"
                                        placeholder="{{ __('advertisements.enter_your_bid') }}">
                                </div>
                                @endif
                                <div class="mt-4">
                                    <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                        {{ __('advertisements.place_bid') }}
                                    </button>
                                </div>
                            </div>
                            </form>

                            <div class="mt-4">
                                @if ($isFavorite)
                                <form action="{{ route('removeMyFavorite', $advertisement->id) }}" method="post" class="space-y-6">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                        {{ __('advertisements.remove_from_favorites') }}
                                    </button>
                                </form>
                                @else
                                <form action="{{ route('addMyFavorite', $advertisement->id) }}" method="post" class="space-y-6">
                                    @csrf
                                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                        {{ __('advertisements.add_to_favorites') }}
                                    </button>
                                </form>
                                @endif
                            </div>
                            @else
                            <br />
                            <p class="block text-lg font-medium text-gray-700 dark:text-gray-300">{{ __('advertisements.max_bids') }}</p>
                            @endif
                            @endif

                            <div class="mt-8">
                                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">{{ __('advertisements.related_advertisements') }}</h2>
                                @if ($relatedAdvertisements->isNotEmpty())
                                <ul class="space-y-4">
                                    @foreach ($relatedAdvertisements as $relatedAdvertisement)
                                    <li class="bg-white dark:bg-gray-800 border rounded-md shadow-sm dark:border-gray-700 p-4 flex items-center justify-between">
                                        <div>
                                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $relatedAdvertisement->relatedAdvertisement->title }}</h3>
                                        </div>
                                        <a href="{{ route('viewAdvertisement', $relatedAdvertisement->related_advertisement_id) }}"
                                            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600 dark:focus:ring-indigo-500">
                                            {{ __('advertisements.goto') }}
                                        </a>
                                    </li>
                                    @endforeach
                                </ul>
                                @else
                                <p class="text-gray-500 dark:text-gray-400">{{ __('advertisements.no_related_advertisements') }}</p>
                                @endif
                            </div>

                            @if ($advertisement->is_rentable)
                            <div class="mt-8">
                                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">{{ __('advertisements.reviews') }}</h2>

                                <div class="mb-6 p-6 bg-white dark:bg-gray-800 rounded-md shadow-md">
                                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-2">{{ __('advertisements.create_review') }}</h3>
                                    <form action="{{ route('reviewAdvertisement', $advertisement->id) }}" method="post" class="space-y-4">
                                        @csrf
                                        <div>
                                            <label for="review" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('advertisements.create_review') }}</label>
                                            <textarea id="review" name="review" rows="4" required
                                                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 p-2"></textarea>
                                        </div>
                                        <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                            {{ __('advertisements.place_review') }}
                                        </button>
                                    </form>
                                </div>

                                @if ($reviews->isNotEmpty())
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">{{ __('advertisements.existing_reviews') }}</h3>
                                <ul class="space-y-4">
                                    @foreach ($reviews as $review)
                                    <li class="p-6 bg-white dark:bg-gray-800 rounded-md shadow-md">
                                        <div class="flex items-center mb-2">
                                            <h4 class="text-md font-semibold text-gray-700 dark:text-gray-300">{{ $review->user->name }}</h4>
                                        </div>
                                        <p class="text-gray-600 dark:text-gray-400">{{ $review->review }}</p>
                                    </li>
                                    @endforeach
                                </ul>
                                @else
                                <p class="text-gray-600 dark:text-gray-400">{{ __('advertisements.no_reviews_yet') }}</p>
                                @endif
                            </div>
                            @endif
                    </div>
                </div>
            </div>
        </div>
</x-app-layout>