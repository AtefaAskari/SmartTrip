<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Upload Photo - {{ $trip->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('galleries.store', $trip) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-1">Image *</label>
                            <input type="file" name="image" accept="image/*" required class="w-full">
                            @error('image') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-1">Caption</label>
                            <input type="text" name="caption" value="{{ old('caption') }}" class="w-full rounded-md border-gray-300">
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-1">Review / Memory</label>
                            <textarea name="review" rows="3" class="w-full rounded-md border-gray-300">{{ old('review') }}</textarea>
                        </div>

                        <div class="flex justify-end gap-2">
                            <a href="{{ route('galleries.index', $trip) }}" class="bg-gray-500 text-white px-4 py-2 rounded">Cancel</a>
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Upload</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>