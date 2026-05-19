<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            🤖 AI Recommendations History
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-2xl font-bold">Your Past Suggestions</h3>
                        <a href="{{ route('recommendations.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-full shadow transition flex items-center gap-2">
                            🤖 New Recommendation
                        </a>
                    </div>

                    @if(session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-3 rounded mb-4">{{ session('success') }}</div>
                    @endif

                    @if($myRecommendations->count())
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            @foreach($myRecommendations as $rec)
                                <div class="border rounded-xl p-4 hover:shadow-lg transition flex gap-4">
                                    @php $firstDest = $rec->suggestions['destinations'][0] ?? null; @endphp
                                    @if($firstDest && isset($firstDest['image']))
                                        <img src="{{ $firstDest['image'] }}" class="w-20 h-20 rounded-lg object-cover">
                                    @else
                                        <div class="w-20 h-20 bg-gray-200 rounded-lg flex items-center justify-center">🌍</div>
                                    @endif
                                    <div class="flex-1">
                                        <p class="font-bold">{{ $rec->country }}</p>
                                        <p class="text-sm text-gray-600">Budget: ${{ number_format($rec->budget, 0) }}</p>
                                        <p class="text-sm text-gray-500 line-clamp-2">{{ $rec->interests }}</p>
                                        <a href="{{ route('recommendations.show', $rec) }}" class="text-blue-500 text-sm mt-2 inline-block">View Details →</a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12 bg-gray-50 rounded-xl">
                            <p class="text-gray-500">No recommendations yet. Click "New Recommendation" to start.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>