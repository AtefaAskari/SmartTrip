<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap justify-between items-center gap-4">
            <h2 class="text-3xl font-black gradient-text">My Journeys</h2>
            <a href="{{ route('trips.create') }}" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-full shadow-lg transition-transform hover:scale-105">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                New Trip
            </a>
        </div>
    </x-slot>

    <div class="py-12 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-10">
        @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-400 text-green-700 p-4 rounded-md shadow-sm">{{ session('success') }}</div>
        @endif

        <!-- My Trips Section -->
        <div>
            <h3 class="text-2xl font-bold text-gray-800 dark:text-white mb-6 flex items-center gap-2">
                <span>✈️</span> My Trips
            </h3>
            @if($myTrips->count())
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($myTrips as $trip)
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-1 overflow-hidden group">
                            <div class="h-2 bg-gradient-to-r from-indigo-400 to-purple-500"></div>
                            <div class="p-5">
                                <div class="flex justify-between items-start">
                                    <h4 class="text-lg font-bold text-gray-800 dark:text-white">{{ $trip->name }}</h4>
                                    <span class="text-xs px-2 py-1 rounded-full {{ $trip->visibility == 'public' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300' : 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300' }}">
                                        {{ $trip->visibility == 'public' ? '🌍 Public' : '🔒 Private' }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $trip->start_date->format('M d, Y') }} - {{ $trip->end_date->format('M d, Y') }}</p>
                                <div class="mt-4 flex justify-between items-center">
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Budget</p>
                                        <p class="font-semibold text-gray-800 dark:text-white">${{ number_format($trip->total_budget,2) }}</p>
                                    </div>
                                    <div class="flex gap-3">
                                        <a href="{{ route('trips.show', $trip) }}" class="text-indigo-600 dark:text-indigo-400 font-medium hover:underline">View</a>
                                        <a href="{{ route('trips.edit', $trip) }}" class="text-amber-600 dark:text-amber-400 font-medium hover:underline">Edit</a>
                                        <form action="{{ route('trips.destroy', $trip) }}" method="POST" onsubmit="return confirm('Delete this trip?')" class="inline">
                                            @csrf @method('DELETE')
                                            <button class="text-red-600 dark:text-red-400 font-medium hover:underline">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-8">{{ $myTrips->links() }}</div>
            @else
                <div class="bg-white/50 dark:bg-gray-800/50 border border-dashed border-gray-300 dark:border-gray-600 rounded-2xl p-12 text-center">
                    <p class="text-gray-500 dark:text-gray-400">No trips yet. Start your first adventure →</p>
                    <a href="{{ route('trips.create') }}" class="mt-3 inline-block text-indigo-600 font-semibold">Create Trip</a>
                </div>
            @endif
        </div>

        <!-- Shared With Me Section (grid, not list) -->
        <div>
            <h3 class="text-2xl font-bold text-gray-800 dark:text-white mb-6 flex items-center gap-2">
                <span>👥</span> Shared With Me
            </h3>
            @if($sharedTrips->count())
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($sharedTrips as $trip)
                        <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl shadow-md p-5 hover:shadow-xl transition-all">
                            <h4 class="text-lg font-bold text-gray-800 dark:text-white">{{ $trip->name }}</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Owner: {{ $trip->user->name }}</p>
                            <div class="mt-3">
                                <a href="{{ route('trips.show', $trip) }}" class="text-indigo-600 dark:text-indigo-400 font-semibold inline-flex items-center gap-1">View Trip →</a>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-8">{{ $sharedTrips->links() }}</div>
            @else
                <div class="bg-white/50 dark:bg-gray-800/50 border border-dashed border-gray-300 dark:border-gray-600 rounded-2xl p-12 text-center">
                    <p class="text-gray-500 dark:text-gray-400">No trips shared with you yet.</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>