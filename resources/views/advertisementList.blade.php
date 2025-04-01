<div>
    @if ($advertisements->isNotEmpty())
        @foreach ($advertisements as $advertisement)
            <x-advertisement>
                <x-slot:title>{{ $advertisement->title }}</x-slot:title>
                <x-slot:price>{{ number_format($advertisement->price, 2, ',', '.') }}</x-slot:price>
                <x-slot:information>{{ $advertisement->information }}</x-slot:information>
                <x-slot:created_at>{{ $advertisement->created_at->format('d-m-Y H:i') }}</x-slot:created_at>
            </x-advertisement>
        @endforeach
    @else
        <p>Geen advertenties gevonden.</p>
    @endif
</div>