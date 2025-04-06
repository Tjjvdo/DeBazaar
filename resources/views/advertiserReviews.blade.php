<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('advertisements.advertiser_reviews') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg relative">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div class="mb-8 p-6 bg-white dark:bg-gray-800 rounded-md shadow-md border-b dark:border-gray-700">
                            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">{{ __('advertisements.seller_information') }}</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-gray-700 dark:text-gray-300"><span class="font-semibold">{{ __('advertisements.name') }}:</span> {{ $advertiser->name }}</p>
                                    <p class="text-gray-700 dark:text-gray-300"><span class="font-semibold">{{ __('advertisements.email') }}:</span> {{ $advertiser->email }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-700 dark:text-gray-300"><span class="font-semibold">{{ __('advertisements.joined_on') }}:</span> {{ $advertiser->created_at->format('d-m-Y H:i') }}</p>
                                    <p class="text-gray-700 dark:text-gray-300"><span class="font-semibold">{{ __('advertisements.amount_of_advertisements') }}:</span> {{ $amountOfAdvertisements }}</p>
                                </div>
                            </div>
                            {{-- Je zou hier eventueel een profielfoto kunnen toevoegen --}}
                            {{-- <img src="{{ asset('path/to/profile.jpg') }}" alt="Profielfoto van {{ $advertiser->name }}" class="mt-4 rounded-full w-24 h-24 object-cover"> --}}
                        </div>

                        <div class="mt-8">
                            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">{{ __('advertisements.reviews') }}</h2>

                            <div class="mb-6 p-6 bg-white dark:bg-gray-800 rounded-md shadow-md">
                                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-2">{{ __('advertisements.create_review') }}</h3>
                                <form action="{{ route('postAdvertiserReview', $advertiser->id) }}" method="post" class="space-y-4">
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
                                        <h4 class="text-md font-semibold text-gray-700 dark:text-gray-300">{{ $review->advertiser->name }}</h4>
                                    </div>
                                    <p class="text-gray-600 dark:text-gray-400">{{ $review->review }}</p>
                                </li>
                                @endforeach
                            </ul>
                            @else
                            <p class="text-gray-600 dark:text-gray-400">{{ __('advertisements.no_reviews_yet') }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>