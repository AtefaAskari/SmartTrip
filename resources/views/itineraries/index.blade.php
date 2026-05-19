<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Itinerary: {{ $trip->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold">Daily Schedule</h3>
                        @if($trip->user_id === Auth::id() || $trip->collaborators()->where('user_id', Auth::id())->whereIn('role', ['editor', 'admin'])->exists())
                            <a href="{{ route('itineraries.create', $trip) }}" class="bg-blue-500 text-white px-3 py-1 rounded">Add Activity</a>
                        @endif
                    </div>

                    @if($itineraries->isEmpty())
                        <p class="text-gray-500">No itinerary items yet. Start planning your days!</p>
                    @else
                        @foreach($days as $dayNumber => $items)
                            <div class="border rounded-lg mb-4 overflow-hidden">
                                <div class="bg-gray-100 px-4 py-2 font-bold">
                                    Day {{ $dayNumber }} - {{ $items->first()->day_date->format('F j, Y') }}
                                </div>
                                <div class="p-4 space-y-3">
                                    @foreach($items as $item)
                                        <div class="flex justify-between items-start border-b pb-2">
                                            <div>
                                                <div class="font-semibold">{{ $item->title }}</div>
                                                <div class="text-sm text-gray-600">
                                                    @if($item->start_time) {{ \Carbon\Carbon::parse($item->start_time)->format('g:i A') }} @endif
                                                    @if($item->end_time) - {{ \Carbon\Carbon::parse($item->end_time)->format('g:i A') }} @endif
                                                    @if($item->location) • {{ $item->location }} @endif
                                                </div>
                                                <div class="text-sm">{{ $item->description }}</div>
                                                @if($item->estimated_cost > 0)
                                                    <div class="text-xs text-green-600">Estimated cost: ${{ number_format($item->estimated_cost, 2) }}</div>
                                                @endif
                                            </div>
                                            @if($trip->user_id === Auth::id() || $trip->collaborators()->where('user_id', Auth::id())->whereIn('role', ['editor', 'admin'])->exists())
                                                <div class="flex gap-2">
                                                    <a href="{{ route('itineraries.edit', [$trip, $item]) }}" class="text-yellow-500">Edit</a>
                                                    <form action="{{ route('itineraries.destroy', [$trip, $item]) }}" method="POST" onsubmit="return confirm('Delete this activity?')">
                                                        @csrf @method('DELETE')
                                                        <button class="text-red-500">Delete</button>
                                                    </form>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    @endif
                    <div class="mt-4">
                        <a href="{{ route('trips.show', $trip) }}" class="text-blue-500">← Back to Trip</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>