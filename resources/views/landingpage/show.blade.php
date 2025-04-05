<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                @foreach ($landingPage->component_order as $component)
                    @if ($component === 'information')
                        <div class="mb-6">
                            <h2 class="text-xl font-bold text-gray-700 dark:text-gray-300">{{ __('landingpage.about_us') }}</h2>
                            <p class="text-gray-700 dark:text-gray-300">{{ $landingPage->info_content }}</p>
                        </div>
                    @elseif ($component === 'image' && $landingPage->image_path)
                        <div class="mb-6">
                            <img src="{{ asset('storage/' . $landingPage->image_path) }}" alt="Landing Image" class="w-full max-w-xs h-auto rounded-lg shadow-md">
                        </div>
                    @elseif ($component === 'advertisements')
                        <div class="mb-6">
                            <h2 class="text-xl font-bold mb-4 text-gray-700 dark:text-gray-300">{{ __('landingpage.our_ads') }}</h2>
                            <div class="p-6 text-gray-900 dark:text-gray-100">
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @foreach ($ads as $advertisement)
                                        <div>
                                            <x-advertisement class="p-4 border rounded-lg shadow-md dark:border-gray-700">
                                                <x-slot:title>{{ $advertisement->title }}</x-slot:title>
                                                <x-slot:price>{{ number_format($advertisement->price, 2, ',', '.') }}</x-slot:price>
                                                <x-slot:information>{{ $advertisement->information }}</x-slot:information>
                                                <x-slot:created_at>{{ $advertisement->created_at->format('d-m-Y H:i') }}</x-slot:created_at>
                                            </x-advertisement>
                        
                                            <div class="mt-4">
                                                <a href="{{ route('viewAdvertisement', $advertisement->id) }}"
                                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                                    {{ __('advertisements.shop') }}
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>