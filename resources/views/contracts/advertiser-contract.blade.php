<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('contracts.my_contract') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                @if($contract && $contract->pdf_path && $contract->status == "pending")
                    <h3 class="text-lg text-gray-700 dark:text-gray-300 font-bold mb-4">{{ __('contracts.your_contract') }}</h3>
                    
                    <iframe src="{{ asset('storage/' . $contract->pdf_path) }}" width="100%" height="500px"></iframe>

                    <form method="POST" action="{{ route('contracts.respond') }}">
                        @csrf
                        <div class="mt-4">
                            <button type="submit" name="response" value="accept"
                                class="bg-green-500 text-white px-4 py-2 rounded-lg">
                                {{ __('contracts.accept_contract_button') }}
                            </button>

                            <button type="submit" name="response" value="decline"
                                class="bg-red-500 text-white px-4 py-2 rounded-lg ml-2">
                                {{ __('contracts.decline_contract_button') }}
                            </button>
                        </div>
                    </form>
                @else
                    <p class="text-gray-700 dark:text-gray-300">{{ __('contracts.no_contract_available') }}</p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
