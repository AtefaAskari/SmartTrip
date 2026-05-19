<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Trip') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('trips.update', $trip) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium mb-1">Trip Name *</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $trip->name) }}" required
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                            @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium mb-1">Description</label>
                            <textarea name="description" id="description" rows="3"
                                      class="w-full rounded-md border-gray-300 shadow-sm">{{ old('description', $trip->description) }}</textarea>
                            @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="start_date" class="block text-sm font-medium mb-1">Start Date *</label>
                                <input type="date" name="start_date" id="start_date" value="{{ old('start_date', $trip->start_date->format('Y-m-d')) }}" required
                                       class="w-full rounded-md border-gray-300 shadow-sm">
                                @error('start_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="end_date" class="block text-sm font-medium mb-1">End Date *</label>
                                <input type="date" name="end_date" id="end_date" value="{{ old('end_date', $trip->end_date->format('Y-m-d')) }}" required
                                       class="w-full rounded-md border-gray-300 shadow-sm">
                                @error('end_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="total_budget" class="block text-sm font-medium mb-1">Total Budget ($) *</label>
                                <input type="number" step="0.01" name="total_budget" id="total_budget" value="{{ old('total_budget', $trip->total_budget) }}" required
                                       class="w-full rounded-md border-gray-300 shadow-sm">
                                @error('total_budget') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="visibility" class="block text-sm font-medium mb-1">Visibility *</label>
                                <select name="visibility" id="visibility" required
                                        class="w-full rounded-md border-gray-300 shadow-sm">
                                    <option value="private" {{ old('visibility', $trip->visibility) == 'private' ? 'selected' : '' }}>Private (only me)</option>
                                    <option value="public" {{ old('visibility', $trip->visibility) == 'public' ? 'selected' : '' }}>Public (anyone with link)</option>
                                </select>
                                @error('visibility') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="flex justify-end gap-2">
                            <a href="{{ route('trips.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Cancel</a>
                            <button type="submit" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">Update Trip</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>