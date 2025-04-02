<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __($title) }}
        </h2>
    </x-slot>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if ($advertisements->isNotEmpty())
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
                                        <a href="/Advertisements/{{ $advertisement->id }}/View"
                                            class="bg-blue-500 hover:bg-blue-700 !important text-white font-bold py-2 px-4 rounded">Bekijken</a>
                                        @if (Auth::user()->id == $advertisement->advertiser_id)
                                            <a href="/Advertisements/{{ $advertisement->id }}/Update"
                                                class="bg-green-500 hover:bg-green-700 !important text-white font-bold py-2 px-4 rounded">Update</a>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-center text-gray-600 dark:text-gray-400">
                            Geen advertenties gevonden.
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>