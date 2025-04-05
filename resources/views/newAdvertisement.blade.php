<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('advertisements.advertisement_create') }}
        </h2>
    </x-slot>

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
                    @if ($amountOfBidAdvertisements < 4 || $amountOfRentAdvertisements < 4)
                        <form action="{{ route('newAdvertisements') }}" method="POST" class="space-y-6">
                        @csrf

                        <div>
                            <label for="title"
                                class="block text-lg font-medium text-gray-700 dark:text-gray-300">{{ __('advertisements.title_input') }}</label>
                            <div class="mt-2">
                                <input type="text" id="title" name="title" required
                                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-lg border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 p-2">
                            </div>
                        </div>

                        <div>
                            <label for="price"
                                class="block text-lg font-medium text-gray-700 dark:text-gray-300">{{ __('advertisements.price_input') }}</label>
                            <div class="mt-2">
                                <input type="number" id="price" name="price" required
                                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-lg border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 p-2">
                            </div>
                        </div>

                        <div>
                            <label for="information"
                                class="block text-lg font-medium text-gray-700 dark:text-gray-300">{{ __('advertisements.information_input') }}</label>
                            <div class="mt-2">
                                <textarea id="information" name="information" rows="4" required
                                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-lg border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 p-2"></textarea>
                            </div>
                        </div>

                        <div>
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    @if ($amountOfBidAdvertisements > 3)
                                    <input id="rentable" name="rentable" type="checkbox" checked="true" disabled="true"
                                        class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-offset-gray-800">
                                    <input type="hidden" name="rentable" value="1">
                                    <div class="ml-3 text-lg">
                                        <label for="rentable" class="font-medium text-gray-700 dark:text-gray-300">
                                            {{ __('advertisements.rent') }} ({{ __('advertisements.only_rent') }})
                                        </label>
                                    </div>
                                    @elseif ($amountOfRentAdvertisements > 3)
                                    <input id="rentable" name="rentable" type="checkbox" checked="false" disabled="true"
                                        class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-offset-gray-800">
                                    <div class="ml-3 text-lg">
                                        <label for="rentable" class="font-medium text-gray-700 dark:text-gray-300">
                                            {{ __('advertisements.rent') }} ({{ __('advertisements.only_bid') }})
                                        </label>
                                    </div>
                                    @else
                                    <input id="rentable" name="rentable" type="checkbox"
                                        class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-offset-gray-800">
                                    <div class="ml-3 text-lg">
                                        <label for="rentable" class="font-medium text-gray-700 dark:text-gray-300">
                                            {{ __('advertisements.rent') }}
                                        </label>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div>
                            <button type="submit" class="inline-flex justify-center py-3 px-6 border shadow-sm text-lg font-medium rounded-md text-white focus:outline-none focus:ring-2 focus:ring-offset-2">
                                {{ __('advertisements.create_button') }}
                            </button>
                        </div>
                        </form>

                        <hr class="my-8 border-gray-300 dark:border-gray-600">

                        <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">{{ __('advertisements.upload_csv_advertisements') }}</h2>
                        <form action="{{ route('uploadAdvertisementsCSV') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            <div>
                                <label for="csv_file" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('advertisements.csv_file_label') }}</label>
                                <div class="mt-1">
                                    <input type="file" id="csv_file" name="csv_file" accept=".csv" required
                                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 p-2">
                                </div>
                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ __('advertisements.csv_file_instructions') }}</p>
                            </div>
                            <div>
                                <button type="submit" class="inline-flex justify-center py-3 px-6 border shadow-sm text-lg font-medium rounded-md text-white focus:outline-none focus:ring-2 focus:ring-offset-2">
                                    {{ __('advertisements.upload_button') }}
                                </button>
                            </div>
                        </form>
                        @else
                        <h2>{{ __('advertisements.max_advertisements') }}</h2>
                        @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>