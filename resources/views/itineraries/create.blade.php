<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Add Activity to: {{ $trip->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('itineraries.store', $trip) }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-1">Day Number *</label>
                            <input type="number" name="day_number" min="1" required value="{{ old('day_number') }}" class="w-full rounded-md border-gray-300">
                            @error('day_number') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-1">Activity Title *</label>
                            <input type="text" name="title" required value="{{ old('title') }}" class="w-full rounded-md border-gray-300">
                            @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-1">Description</label>
                            <textarea name="description" rows="3" class="w-full rounded-md border-gray-300">{{ old('description') }}</textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium mb-1">Start Time (optional)</label>
                                <input type="time" name="start_time" value="{{ old('start_time') }}" class="w-full rounded-md border-gray-300">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">End Time (optional)</label>
                                <input type="time" name="end_time" value="{{ old('end_time') }}" class="w-full rounded-md border-gray-300">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-1">Location (optional)</label>
                            <input type="text" name="location" value="{{ old('location') }}" class="w-full rounded-md border-gray-300">
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-1">Estimated Cost ($)</label>
                            <input type="number" step="0.01" name="estimated_cost" value="{{ old('estimated_cost') }}" class="w-full rounded-md border-gray-300">
                        </div>

                        <div class="flex justify-end gap-2">
                            <a href="{{ route('itineraries.index', $trip) }}" class="bg-gray-500 text-white px-4 py-2 rounded">Cancel</a>
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Add Activity</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>