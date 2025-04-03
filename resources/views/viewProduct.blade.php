<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Advertentie bekijken') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <x-advertisement class="p-4 border rounded-lg shadow-md dark:border-gray-700">
                        <x-slot:title>{{ $advertisement->title }}</x-slot:title>
                        <x-slot:price>{{ number_format($advertisement->price, 2, ',', '.') }}</x-slot:price>
                        <x-slot:information>{{ $advertisement->information }}</x-slot:information>
                        <x-slot:created_at>{{ $advertisement->created_at->format('d-m-Y H:i') }}</x-slot:created_at>
                        <x-slot:id>{{ $advertisement->id }}</x-slot:id>
                    </x-advertisement>
                </div>
                <form action="{{ route('bidOnProduct', $advertisement->id) }}" method="post" class="space-y-6">
                    @csrf
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        @if ($advertisement->is_rentable)
                        <p>huren</p>
                        @else
                        <label for="bid"
                            class="block text-lg font-medium text-gray-700 dark:text-gray-300">Hoogste bod: {{$bidding->bid_amount}}</label>
                        <div class="mt-2">
                            <input type="number" id="bid" name="bid" min="{{$bidding->bid_amount + 1}}" 
                                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-lg border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 p-2">
                        </div>
                        <button>Bieden</button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>