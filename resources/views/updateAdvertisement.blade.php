<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('advertisements.advertisement_update') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('postUpdateAdvertisement', $advertisement->id) }}" method="post" class="space-y-6">
                        @csrf

                        <div>
                            <label for="title"
                                class="block text-lg font-medium text-gray-700 dark:text-gray-300">{{ __('advertisements.title_input') }}</label>
                            <div class="mt-2">
                                <input type="text" id="title" name="title"
                                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-lg border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 p-2"
                                    value="{{$advertisement->title}}">
                            </div>
                        </div>

                        <div>
                            <label for="price"
                                class="block text-lg font-medium text-gray-700 dark:text-gray-300">{{ __('advertisements.price_input') }}</label>
                            <div class="mt-2">
                                <input type="text" id="price" name="price"
                                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-lg border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 p-2"
                                    value="{{$advertisement->price}}">
                            </div>
                        </div>

                        <div>
                            <label for="information"
                                class="block text-lg font-medium text-gray-700 dark:text-gray-300">{{ __('advertisements.information_input') }}</label>
                            <div class="mt-2">
                                <textarea id="information" name="information" rows="4"
                                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-lg border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 p-2">{{$advertisement->information}}</textarea>
                            </div>
                        </div>

                        <div>
                            <button type="submit"
                                class="inline-flex justify-center py-3 px-6 border shadow-sm text-lg font-medium rounded-md text-white focus:outline-none focus:ring-2 focus:ring-offset-2">
                                {{ __('advertisements.update_button') }}
                            </button>
                        </div>

                    </form>

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

                        <form action="{{ route('postUpdateAdvertisementRelation', $advertisement->id) }}" method="post" class="space-y-6">
                            @csrf

                            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">{{ __('advertisements.add_relation') }}</h2>

                            <div>
                                @if (!$advertisements->isEmpty())
                                <label for="relationId" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('advertisements.relation') }}</label>
                                <div class="mt-1">
                                    <select id="relationId" name="relationId"
                                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 p-2">
                                        <option value="">{{ __('advertisements.select_related_advertisement') }}</option>
                                        @foreach ($advertisements as $relatedAd)
                                        @if ($relatedAd->id !== $advertisement->id)
                                        <option value="{{ $relatedAd->id }}">{{ $relatedAd->title }}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                </div>
                                @else
                                <p class="text-gray-500 dark:text-gray-400">{{ __('advertisements.no_relation_advertisements') }}</p>
                                @endif
                            </div>

                            <div>
                                <button type="submit"
                                    class="inline-flex justify-center py-3 px-6 border shadow-sm text-lg font-medium rounded-md text-white focus:outline-none focus:ring-2 focus:ring-offset-2">
                                    {{ __('advertisements.add_relation') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>