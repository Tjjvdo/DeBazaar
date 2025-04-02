<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Advertenties') }}
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
            </div>
        </div>
    </div>
</x-app-layout>