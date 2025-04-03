<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('advertisements.advertisement_create') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if ($amountOfAdvertisements < 4)
                        <form action="{{ route('newAdvertisements') }}" method="POST" class="space-y-6">
                        @csrf

                        <div>
                            <label for="title"
                                class="block text-lg font-medium text-gray-700 dark:text-gray-300">{{ __('advertisements.title_input') }}</label>
                            <div class="mt-2">
                                <input type="text" id="title" name="title"
                                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-lg border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 p-2">
                            </div>
                        </div>

                        <div>
                            <label for="price"
                                class="block text-lg font-medium text-gray-700 dark:text-gray-300">{{ __('advertisements.price_input') }}</label>
                            <div class="mt-2">
                                <input type="number" id="price" name="price"
                                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-lg border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 p-2">
                            </div>
                        </div>

                        <div>
                            <label for="information"
                                class="block text-lg font-medium text-gray-700 dark:text-gray-300">{{ __('advertisements.information_input') }}</label>
                            <div class="mt-2">
                                <textarea id="information" name="information" rows="4"
                                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-lg border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 p-2"></textarea>
                            </div>
                        </div>

                        <div>
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="rentable" name="rentable" type="checkbox"
                                        class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-offset-gray-800">
                                </div>
                                <div class="ml-3 text-lg">
                                    <label for="rentable" class="font-medium text-gray-700 dark:text-gray-300">
                                        Verhuren
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div>
                            <button type="submit" class="inline-flex justify-center py-3 px-6 border shadow-sm text-lg font-medium rounded-md text-white focus:outline-none focus:ring-2 focus:ring-offset-2">
                                {{ __('advertisements.create_button') }}
                            </button>
                        </div>
                        </form>
                        @else
                        <h2>U heeft het maximale aantal advertenties geplaats</h2>
                        @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>