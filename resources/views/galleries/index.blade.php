<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Travel Gallery - {{ $trip->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success')) <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div> @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @php
                        $canUpload = ($trip->user_id === Auth::id() || $trip->collaborators()->where('user_id', Auth::id())->whereIn('role', ['editor', 'admin'])->exists());
                    @endphp
                    @if($canUpload)
                        <div class="mb-6">
                            <a href="{{ route('galleries.create', $trip) }}" class="bg-blue-500 text-white px-4 py-2 rounded">Upload New Photo</a>
                        </div>
                    @endif

                    @if($images->count())
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($images as $image)
                                <div class="border rounded overflow-hidden shadow">
                                    <img src="{{ Storage::url($image->image_path) }}" class="w-full h-48 object-cover">
                                    <div class="p-3">
                                        <p class="font-semibold">{{ $image->caption ?? 'No caption' }}</p>
                                        <p class="text-sm text-gray-600">{{ $image->review ?? '' }}</p>
                                        <p class="text-xs text-gray-400 mt-1">Uploaded by {{ $image->user->name }}</p>
                                        @if($canUpload || $image->user_id === Auth::id())
                                            <form action="{{ route('galleries.destroy', [$trip, $image]) }}" method="POST" onsubmit="return confirm('Delete this photo?')" class="mt-2">
                                                @csrf @method('DELETE')
                                                <button class="text-red-500 text-sm">Delete</button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500">No photos yet. Share your travel memories!</p>
                    @endif
                    <div class="mt-4">
                        <a href="{{ route('trips.show', $trip) }}" class="text-blue-500">← Back to Trip</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>