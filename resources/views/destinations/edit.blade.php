<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Destination: {{ $destination->city }}, {{ $destination->country }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('destinations.update', [$trip, $destination]) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-1">City *</label>
                            <input type="text" name="city" value="{{ old('city', $destination->city) }}" required class="w-full rounded-md border-gray-300">
                            @error('city') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-1">Country *</label>
                            <input type="text" name="country" value="{{ old('country', $destination->country) }}" required class="w-full rounded-md border-gray-300">
                            @error('country') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium mb-1">Arrival Date *</label>
                                <input type="date" name="arrival_date" value="{{ old('arrival_date', $destination->arrival_date->format('Y-m-d')) }}" required class="w-full rounded-md border-gray-300">
                                @error('arrival_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Departure Date *</label>
                                <input type="date" name="departure_date" value="{{ old('departure_date', $destination->departure_date->format('Y-m-d')) }}" required class="w-full rounded-md border-gray-300">
                                @error('departure_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-1">Notes</label>
                            <textarea name="notes" rows="3" class="w-full rounded-md border-gray-300">{{ old('notes', $destination->notes) }}</textarea>
                        </div>

                        <div class="flex justify-end gap-2">
                            <a href="{{ route('trips.show', $trip) }}" class="bg-gray-500 text-white px-4 py-2 rounded">Cancel</a>
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Update Destination</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>