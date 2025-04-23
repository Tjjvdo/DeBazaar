<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $landingPage->user->name }}
            </h2>
        </div>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg p-6" style="background-color: {{ $landingPage->color }}">
                @foreach ($landingPage->component_order as $component)
                @if ($component === 'information')
                <div class="mb-6">
                    <h2 class="text-xl font-bold text-color-dynamic">{{ __('landingpage.about_us') }}</h2>
                    <p class="text-color-dynamic">{{ $landingPage->info_content }}</p>
                </div>
                @elseif ($component === 'image' && $landingPage->image_path)
                <div class="mb-6">
                    <img src="{{ asset('storage/' . $landingPage->image_path) }}" alt="Landing Image" class="w-full max-w-xs h-auto rounded-lg shadow-md">
                </div>
                @elseif ($component === 'advertisements')
                <div class="mb-6">
                    <h2 class="text-xl font-bold mb-4 text-color-dynamic">{{ __('landingpage.our_ads') }}</h2>
                    <div class="p-6 text-color-dynamic">

                        <form method="GET" action="{{ route('landingpage.show', $landingPage->slug) }}" class="mb-6 flex items-center space-x-4">
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
                            <a href="{{ route('landingpage.show', $landingPage->slug) }}" class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 self-end">{{ __('advertisements.reset_filter') }}</a>
                        </form>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach ($ads as $advertisement)
                            <div>
                                <x-advertisement class="p-4 border rounded-lg shadow-md dark:border-gray-700">
                                    <x-slot:title>{{ $advertisement->title }}</x-slot:title>
                                    <x-slot:price>{{ number_format($advertisement->price, 2, ',', '.') }}</x-slot:price>
                                    <x-slot:information>{{ $advertisement->information }}</x-slot:information>
                                    <x-slot:created_at>{{ $advertisement->created_at->format('d-m-Y H:i') }}</x-slot:created_at>
                                </x-advertisement>

                                <div class="mt-4 flex space-x-2">
                                    <a href="{{ route('viewAdvertisement', $advertisement->id) }}"
                                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                        {{ __('advertisements.shop') }}
                                    </a>
                                    @if (Auth::user()->id == $advertisement->advertiser_id)
                                    <a href="{{ route('getUpdateAdvertisement', $advertisement->id) }}" class="bg-green-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">{{ __('advertisements.update') }}</a>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="mt-4">
                            {{ $ads->links() }}
                        </div>
                    </div>
                </div>
                @endif
                @endforeach
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const backgroundColor = "{{ $landingPage->color }}";

            function calculateBrightness(hex) {
                hex = hex.replace("#", "");
                let r = parseInt(hex.substring(0, 2), 16);
                let g = parseInt(hex.substring(2, 4), 16);
                let b = parseInt(hex.substring(4, 6), 16);
                return 0.2126 * r + 0.7152 * g + 0.0722 * b;
            }

            const brightness = calculateBrightness(backgroundColor);

            const textColor = brightness < 128 ? 'white' : 'black';

            document.querySelectorAll('.text-color-dynamic').forEach(function(element) {
                element.style.color = textColor;
            });
        });
    </script>

</x-app-layout>