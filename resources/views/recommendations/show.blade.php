<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            ✨ Your Personalized Recommendations for {{ $recommendation->country }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Summary -->
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-5 mb-8">
                        <p class="text-gray-700"><strong>Budget:</strong> ${{ number_format($recommendation->budget, 2) }}</p>
                        <p class="text-gray-700"><strong>Interests:</strong> {{ $recommendation->interests }}</p>
                    </div>

                    @php $suggestions = $recommendation->suggestions; @endphp

                    <!-- Destinations Section with Images -->
                    <h3 class="text-2xl font-bold mb-4 flex items-center gap-2">🏝️ Top Destinations</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        @foreach($suggestions['destinations'] as $dest)
                            <div class="bg-white rounded-2xl shadow-md overflow-hidden hover:shadow-xl transition">
                                <img src="{{ $dest['image'] }}" alt="{{ $dest['name'] }}" class="w-full h-48 object-cover">
                                <div class="p-4">
                                    <h4 class="font-bold text-lg">{{ $dest['name'] }}</h4>
                                    <p class="text-gray-500 text-sm">{{ $dest['description'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Hotels with Images -->
                    <h3 class="text-2xl font-bold mb-4 flex items-center gap-2">🏨 Recommended Hotels</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-5 mb-8">
                        @foreach($suggestions['hotels'] as $hotel)
                            <div class="bg-white rounded-xl shadow overflow-hidden hover:shadow-lg transition">
                                <img src="{{ $hotel['image'] }}" class="w-full h-40 object-cover">
                                <div class="p-3">
                                    <h4 class="font-semibold">{{ $hotel['name'] }}</h4>
                                    <p class="text-sm text-green-600">{{ $hotel['price'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Restaurants with Images -->
                    <h3 class="text-2xl font-bold mb-4 flex items-center gap-2">🍽️ Best Restaurants</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-5 mb-8">
                        @foreach($suggestions['restaurants'] as $rest)
                            <div class="bg-white rounded-xl shadow overflow-hidden">
                                <img src="{{ $rest['image'] }}" class="w-full h-40 object-cover">
                                <div class="p-3">
                                    <h4 class="font-semibold">{{ $rest['name'] }}</h4>
                                    <p class="text-sm text-gray-500">Cuisine: {{ $rest['cuisine'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Activities with Images -->
                    <h3 class="text-2xl font-bold mb-4 flex items-center gap-2">🎯 Must-Do Activities</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-5 mb-8">
                        @foreach($suggestions['activities'] as $act)
                            <div class="bg-white rounded-xl shadow overflow-hidden">
                                <img src="{{ $act['image'] }}" class="w-full h-40 object-cover">
                                <div class="p-3">
                                    <h4 class="font-semibold">{{ $act['name'] }}</h4>
                                    <p class="text-sm text-gray-500">⏱️ {{ $act['duration'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="flex justify-between mt-6">
                        <a href="{{ route('recommendations.index') }}" class="text-blue-600 hover:underline">← Back</a>
                        <form action="{{ route('recommendations.destroy', $recommendation) }}" method="POST" onsubmit="return confirm('Delete this recommendation?')">
                            @csrf @method('DELETE')
                            <button class="text-red-500 hover:underline">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>