<x-app-layout>
    <x-slot name="header"><h2 class="text-2xl font-bold">Weather Forecast for {{ $trip->name }}</h2></x-slot>
    <div class="py-12 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow p-6">
            @if($forecasts->count())
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                    @foreach($forecasts as $f)
                        <div class="text-center p-3 border rounded-xl">
                            <div class="font-bold">{{ $f->forecast_date->format('M d') }}</div>
                            <div class="text-3xl my-2">{{ $f->icon }}</div>
                            <div>{{ $f->condition }}</div>
                            <div class="text-sm">{{ $f->temp_high }}° / {{ $f->temp_low }}°</div>
                        </div>
                    @endforeach
                </div>
            @else
                <p>No weather data available.</p>
            @endif
            <a href="{{ route('trips.show', $trip) }}" class="mt-4 inline-block text-indigo-600">← Back</a>
        </div>
    </div>
</x-app-layout>