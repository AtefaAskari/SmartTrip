<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Activity: {{ $itinerary->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('itineraries.update', [$trip, $itinerary]) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-1">Day Number *</label>
                            <input type="number" name="day_number" min="1" required value="{{ old('day_number', $itinerary->day_number) }}" class="w-full rounded-md border-gray-300">
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-1">Activity Title *</label>
                            <input type="text" name="title" required value="{{ old('title', $itinerary->title) }}" class="w-full rounded-md border-gray-300">
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-1">Description</label>
                            <textarea name="description" rows="3" class="w-full rounded-md border-gray-300">{{ old('description', $itinerary->description) }}</textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium mb-1">Start Time</label>
                                <input type="time" name="start_time" value="{{ old('start_time', $itinerary->start_time ? date('H:i', strtotime($itinerary->start_time)) : '') }}" class="w-full rounded-md border-gray-300">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">End Time</label>
                                <input type="time" name="end_time" value="{{ old('end_time', $itinerary->end_time ? date('H:i', strtotime($itinerary->end_time)) : '') }}" class="w-full rounded-md border-gray-300">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-1">Location</label>
                            <input type="text" name="location" value="{{ old('location', $itinerary->location) }}" class="w-full rounded-md border-gray-300">
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-1">Estimated Cost ($)</label>
                            <input type="number" step="0.01" name="estimated_cost" value="{{ old('estimated_cost', $itinerary->estimated_cost) }}" class="w-full rounded-md border-gray-300">
                        </div>

                        <div class="flex justify-end gap-2">
                            <a href="{{ route('itineraries.index', $trip) }}" class="bg-gray-500 text-white px-4 py-2 rounded">Cancel</a>
                            <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded">Update Activity</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>