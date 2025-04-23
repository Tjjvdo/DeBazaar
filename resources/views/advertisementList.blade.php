<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                @if ($title === 'Advertisements')
                {{ __('advertisements.advertisements') }}
                @elseif ($title === 'My advertisements')
                {{ __('advertisements.my_advertisements') }}
                @elseif($title === 'PurchaseHistory')
                {{ __('advertisements.purchase_history') }}
                @elseif($title === 'Favorites')
                {{ __('advertisements.favorites') }}
                @endif
            </h2>

            <div class="space-x-2">
                <a href="{{ route('myPurchases') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150">
                    {{ __('messages.purchaseHistory') }}
                </a>
                <a href="{{ route('myFavorites') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150">
                    {{ __('messages.favorites') }}
                </a>
            </div>
        </div>
    </x-slot>

    @if (session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
        <strong class="font-bold">{{ __('Success') }}!</strong>
        <span class="block sm:inline">{{ session('success') }}</span>
        <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
            <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                <title>Close</title>
                <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.15 2.759 3.152a1.2 1.2 0 0 1 0 1.697z" />
            </svg>
        </span>
    </div>
    @endif

    @if (session('warning'))
    <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative mb-4" role="alert">
        <strong class="font-bold">{{ __('Warning') }}!</strong>
        <span class="block sm:inline">{{ session('warning') }}</span>
        <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
            <svg class="fill-current h-6 w-6 text-yellow-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                <title>Close</title>
                <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.15 2.759 3.152a1.2 1.2 0 0 1 0 1.697z" />
            </svg>
        </span>
    </div>
    @endif

    @if ($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
        <strong class="font-bold">{{ __('Error') }}!</strong>
        <ul class="list-disc ml-4">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
        <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
            <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                <title>Close</title>
                <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.15 2.759 3.152a1.2 1.2 0 0 1 0 1.697z" />
            </svg>
        </span>
    </div>
    @endif

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if ($advertisements->isNotEmpty())

                    <form method="GET" action="{{ route('advertisements') }}" class="mb-6 flex items-center space-x-4">
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('advertisements.filter_title') }}</label>
                            <input type="text" name="title" id="title" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 p-2" value="{{ request('title') }}">
                        </div>

                        <div>
                            <label for="price_min" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('advertisements.min_price') }}</label>
                            <input type="number" name="price_min" id="price_min" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 p-2" value="{{ request('price_min') }}">
                        </div>

                        <div>
                            <label for="price_max" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('advertisements.max_price') }}</label>
                            <input type="number" name="price_max" id="price_max" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 p-2" value="{{ request('price_max') }}">
                        </div>

                        <div>
                            <label for="is_rentable" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('advertisements.rentable') }}</label>
                            <select name="is_rentable" id="is_rentable" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 p-2">
                                <option value="">{{ __('advertisements.all') }}</option>
                                <option value="1" {{ request('is_rentable') === '1' ? 'selected' : '' }}>{{ __('advertisements.yes') }}</option>
                                <option value="0" {{ request('is_rentable') === '0' ? 'selected' : '' }}>{{ __('advertisements.no') }}</option>
                            </select>
                        </div>

                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline self-end">{{ __('advertisements.filter') }}</button>
                        <a href="{{ route('advertisements') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 self-end">{{ __('advertisements.reset_filter') }}</a>
                    </form>

                    <br/>
                    <br/>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach ($advertisements as $advertisement)
                        <div>
                            <x-advertisement class="p-4 border rounded-lg shadow-md dark:border-gray-700">
                                <x-slot:title>{{ $advertisement->title }}</x-slot:title>
                                <x-slot:price>{{ number_format($advertisement->price, 2, ',', '.') }}</x-slot:price>
                                <x-slot:information>{{ $advertisement->information }}</x-slot:information>
                                <x-slot:created_at>{{ $advertisement->created_at->format('d-m-Y H:i') }}</x-slot:created_at>
                            </x-advertisement>
                            <div class="mt-4 flex space-x-2">
                                <a href="{{ route('viewAdvertisement', $advertisement->id) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">{{ __('advertisements.shop') }}</a>
                                @if (Auth::user()->id == $advertisement->advertiser_id)
                                <a href="{{ route('getUpdateAdvertisement', $advertisement->id) }}" class="bg-green-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">{{ __('advertisements.update') }}</a>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="mt-8">
                        {{ $advertisements->links() }}
                    </div>
                    @else
                    <p class="text-center text-gray-600 dark:text-gray-400">
                        {{ __('advertisements.no_advertisements') }}
                    </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>