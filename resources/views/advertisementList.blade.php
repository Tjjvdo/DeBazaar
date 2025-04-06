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
                @endif
            </h2>

            <div class="space-x-2">
                <a href="{{ route('myPurchases') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150">
                    {{ __('messages.purchaseHistory') }} {{-- Vervang dit door je daadwerkelijke vertaling key --}}
                </a>
                <a href="{{ route('myFavorites') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150">
                    {{ __('messages.favorites') }} {{-- Vervang dit door je daadwerkelijke vertaling key --}}
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
                                <a href="{{ route('viewAdvertisement', $advertisement->id) }}"
                                    class="bg-blue-500 hover:bg-blue-700 !important text-white font-bold py-2 px-4 rounded">{{ __('advertisements.shop') }}</a>
                                @if (Auth::user()->id == $advertisement->advertiser_id)
                                <a href="{{ route('getUpdateAdvertisement', $advertisement->id) }}"
                                    class="bg-green-500 hover:bg-green-700 !important text-white font-bold py-2 px-4 rounded">{{ __('advertisements.update') }}</a>
                                @endif
                            </div>
                        </div>
                        @endforeach
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