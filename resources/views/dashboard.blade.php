<x-app-layout>
    <x-slot name="header">
        <h2 class="text-3xl font-black gradient-text">Dashboard</h2>
    </x-slot>

    <div class="py-12 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-8">
        <!-- Hero Welcome Card -->
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500 shadow-2xl">
            <div class="absolute inset-0 bg-black/20"></div>
            <div class="relative p-8 text-white">
                <h1 class="text-3xl font-extrabold">Welcome back, {{ Auth::user()->name }}! 👋</h1>
                <p class="mt-2 text-indigo-100 max-w-xl">Your next adventure is just a click away. Plan, collaborate, and explore with SmartTrip AI.</p>
                <div class="mt-6 flex flex-wrap gap-3">
                    <a href="{{ route('trips.create') }}" class="inline-flex items-center gap-2 bg-white text-indigo-700 px-5 py-2.5 rounded-full font-semibold shadow-lg hover:shadow-xl transition-transform hover:scale-105">
                        ✨ Create New Trip
                    </a>
                    <a href="{{ route('recommendations.create') }}" class="inline-flex items-center gap-2 bg-indigo-800/80 backdrop-blur-sm text-white px-5 py-2.5 rounded-full font-semibold border border-white/20 hover:bg-indigo-700 transition">
                        🤖 Get AI Picks
                    </a>
                </div>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-6 hover:shadow-xl transition-all duration-300 hover:-translate-y-1 border border-gray-100 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">My Trips</p>
                        <p class="text-4xl font-black text-indigo-600 dark:text-indigo-400">{{ Auth::user()->trips()->count() }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center text-indigo-600 text-2xl">✈️</div>
                </div>
                <a href="{{ route('trips.index') }}" class="mt-3 inline-block text-sm font-medium text-indigo-500 hover:underline">View all trips →</a>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-6 hover:shadow-xl transition-all duration-300 hover:-translate-y-1 border border-gray-100 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Shared With Me</p>
                        <p class="text-4xl font-black text-teal-600 dark:text-teal-400">{{ Auth::user()->sharedTrips()->count() }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-teal-100 dark:bg-teal-900/30 flex items-center justify-center text-teal-600 text-2xl">👥</div>
                </div>
                <a href="{{ route('trips.index') }}" class="mt-3 inline-block text-sm font-medium text-teal-500 hover:underline">View shared →</a>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-6 hover:shadow-xl transition-all duration-300 hover:-translate-y-1 border border-gray-100 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Unread Alerts</p>
                        <p class="text-4xl font-black text-pink-600 dark:text-pink-400">{{ Auth::user()->notifications()->where('is_read', false)->count() }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-pink-100 dark:bg-pink-900/30 flex items-center justify-center text-pink-600 text-2xl">🔔</div>
                </div>
                <a href="{{ route('notifications.index') }}" class="mt-3 inline-block text-sm font-medium text-pink-500 hover:underline">Check notifications →</a>
            </div>
        </div>

        <!-- Feature Cards Grid (6 features) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @php
                $features = [
                    ['icon'=>'📅','title'=>'Itinerary Planner','desc'=>'Create day-by-day schedules','color'=>'indigo','route'=>route('trips.index')],
                    ['icon'=>'📸','title'=>'Travel Gallery','desc'=>'Upload and share memories','color'=>'blue','route'=>route('trips.index')],
                    ['icon'=>'☁️','title'=>'Weather Forecast','desc'=>'Check conditions for your dates','color'=>'cyan','route'=>route('trips.index')],
                    ['icon'=>'📊','title'=>'Expense Reports','desc'=>'PDF export & charts','color'=>'green','route'=>route('trips.index')],
                    ['icon'=>'👥','title'=>'Group Collaboration','desc'=>'Invite friends to plan together','color'=>'purple','route'=>route('trips.index')],
                    ['icon'=>'🗳️','title'=>'Voting System','desc'=>'Upvote destinations with group','color'=>'orange','route'=>route('trips.index')],
                ];
            @endphp
            @foreach($features as $feature)
                <div class="group bg-white/70 dark:bg-gray-800/70 backdrop-blur-sm rounded-2xl p-6 shadow-sm hover:shadow-2xl transition-all duration-300 hover:-translate-y-2 border border-gray-100 dark:border-gray-700">
                    <div class="text-4xl mb-3">{{ $feature['icon'] }}</div>
                    <h4 class="text-xl font-bold text-gray-800 dark:text-white">{{ $feature['title'] }}</h4>
                    <p class="text-gray-500 dark:text-gray-400 mt-1 text-sm">{{ $feature['desc'] }}</p>
                    <a href="{{ $feature['route'] }}" class="mt-4 inline-flex items-center text-{{ $feature['color'] }}-500 font-semibold text-sm group-hover:translate-x-1 transition-transform">
                        Explore →
                    </a>
                </div>
            @endforeach
        </div>

        <!-- Optional: Recent Trips Preview -->
        @if(Auth::user()->trips()->count() > 0)
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-800 dark:text-white">🗺️ Recent Trips</h3>
                <a href="{{ route('trips.index') }}" class="text-indigo-500 text-sm">See all</a>
            </div>
            <div class="space-y-3">
                @foreach(Auth::user()->trips()->latest()->take(3)->get() as $recentTrip)
                    <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                        <div>
                            <span class="font-medium">{{ $recentTrip->name }}</span>
                            <span class="text-xs text-gray-500 ml-2">{{ $recentTrip->start_date->format('M d') }} - {{ $recentTrip->end_date->format('M d') }}</span>
                        </div>
                        <a href="{{ route('trips.show', $recentTrip) }}" class="text-indigo-500 text-sm">View</a>
                    </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</x-app-layout>