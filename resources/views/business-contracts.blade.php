<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('contracts.title') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <h3 class="text-lg text-gray-700 dark:text-gray-300 font-bold mb-4">{{ __('contracts.pending_contracts') }}</h3>
                
                @foreach($contracts->where('pdf_path', null) as $contract)
                    <div class="mb-4 p-4 border rounded-lg">
                        <p class="text-gray-700 dark:text-gray-300"><strong>{{ __('contracts.company_name') }}</strong> {{ $contract->user->name ?? 'Unknown' }}</p>
                        <p class="text-gray-700 dark:text-gray-300"><strong>{{ __('contracts.email') }}</strong> {{ $contract->user->email ?? 'Unknown' }}</p>
                        <p class="text-gray-700 dark:text-gray-300"><strong>{{ __('contracts.phone_number') }}</strong> {{ $contract->user->phone_number ?? 'Unknown' }}</p>
                        <p class="text-gray-700 dark:text-gray-300"><strong>{{ __('contracts.status') }}</strong> {{ __('contracts.not_uploaded') }}</p>
                        <br>
                        <a href="{{ route('contracts.download', $contract->user_id) }}" class="text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-green-600 dark:hover:bg-green-700 focus:outline-none dark:focus:ring-green-800">
                            {{ __('contracts.generate_contract_button') }}
                        </a>
                    </div>
                @endforeach
                
                <h3 class="text-lg text-gray-700 dark:text-gray-300 font-bold mt-6 mb-4">{{ __('contracts.upload_contracts') }}</h3>

                <form action="{{ route('contracts.upload') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <label for="user_id" class="text-gray-700 dark:text-gray-300">{{ __('contracts.select_company_input') }}</label>
                    <select name="user_id" required class="w-full p-2 border rounded-lg mb-4">
                        <option value="">{{ __('contracts.select_company_option') }}</option>
                        @foreach($contracts->where('pdf_path', null) as $contract)
                            <option value="{{ $contract->user_id }}">{{ $contract->user->name }}</option>
                        @endforeach
                    </select>

                    <label for="contract_pdf" class="text-gray-700 dark:text-gray-300">{{ __('contracts.upload_contract') }}</label>
                    <x-file-input name="contract_pdf" required />

                    <button type="submit" class="text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-green-600 dark:hover:bg-green-700 focus:outline-none dark:focus:ring-green-800">
                        {{ __('contracts.upload_contract_input') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
