<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap justify-between items-center gap-4">
            <h2 class="text-3xl font-black gradient-text">{{ $trip->name }}</h2>
            @if($trip->user_id === Auth::id())
                <div class="flex gap-3">
                    <a href="{{ route('trips.edit', $trip) }}" class="bg-amber-500 text-white px-4 py-2 rounded-full shadow hover:shadow-lg transition">✏️ Edit</a>
                    <a href="{{ route('trips.share.create', $trip) }}" class="bg-emerald-500 text-white px-4 py-2 rounded-full shadow hover:shadow-lg transition">👥 Share</a>
                </div>
            @endif
        </div>
    </x-slot>

    <div class="py-12 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-8">
        <!-- Trip Summary Card -->
        <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl shadow-xl p-6 border border-gray-100 dark:border-gray-700">
            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <p class="text-gray-600 dark:text-gray-300">{{ $trip->description ?? 'No description added.' }}</p>
                    <div class="mt-4 flex flex-wrap gap-4 text-sm">
                        <span class="flex items-center gap-1">📅 {{ $trip->start_date->format('M d, Y') }} - {{ $trip->end_date->format('M d, Y') }}</span>
                        <span class="flex items-center gap-1">{{ $trip->visibility === 'public' ? '🌍 Public' : '🔒 Private' }}</span>
                    </div>
                </div>
                <div class="bg-indigo-50 dark:bg-indigo-900/30 rounded-xl p-4">
                    <div class="flex justify-between text-sm font-medium">Budget: ${{ number_format($trip->total_budget, 2) }}</div>
                    <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                        @php
                            $budgetPercent = ($trip->total_budget > 0) ? min(100, ($totalSpent / $trip->total_budget) * 100) : 0;
                        @endphp
                        <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ $budgetPercent }}%"></div>
                    </div>
                    <div class="flex justify-between mt-2 text-sm">
                        Spent: ${{ number_format($totalSpent, 2) }} | Remaining: ${{ number_format($remainingBudget, 2) }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Tools -->
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('itineraries.index', $trip) }}" class="bg-indigo-100 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300 px-4 py-2 rounded-full text-sm font-semibold hover:bg-indigo-200 transition">📅 Itinerary</a>
            <a href="{{ route('galleries.index', $trip) }}" class="bg-pink-100 dark:bg-pink-900/50 text-pink-700 dark:text-pink-300 px-4 py-2 rounded-full text-sm font-semibold hover:bg-pink-200 transition">📸 Gallery</a>
            <a href="{{ route('weather.index', $trip) }}" class="bg-cyan-100 dark:bg-cyan-900/50 text-cyan-700 dark:text-cyan-300 px-4 py-2 rounded-full text-sm font-semibold hover:bg-cyan-200 transition">☁️ Weather</a>
            <a href="{{ route('reports.show', $trip) }}" class="bg-red-100 dark:bg-red-900/50 text-red-700 dark:text-red-300 px-4 py-2 rounded-full text-sm font-semibold hover:bg-red-200 transition">📊 Reports</a>
            @if($trip->user_id === Auth::id())
                <a href="{{ route('collaborations.index', $trip) }}" class="bg-purple-100 dark:bg-purple-900/50 text-purple-700 dark:text-purple-300 px-4 py-2 rounded-full text-sm font-semibold hover:bg-purple-200 transition">👥 Collaborators</a>
            @endif
        </div>

        <!-- Destinations and Expenses 2-column layout -->
        <div class="grid lg:grid-cols-2 gap-6">
            <!-- Left: Destinations -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-6">
                <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-4">📍 Destinations</h3>
                @if($canEdit)
                    <form action="{{ route('destinations.store', $trip) }}" method="POST" class="mb-6 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                        @csrf
                        <div class="grid grid-cols-2 gap-3">
                            <input type="text" name="city" placeholder="City" required class="rounded-md border-gray-300 dark:bg-gray-800">
                            <input type="text" name="country" placeholder="Country" required class="rounded-md border-gray-300 dark:bg-gray-800">
                            <input type="date" name="arrival_date" required class="rounded-md border-gray-300 dark:bg-gray-800">
                            <input type="date" name="departure_date" required class="rounded-md border-gray-300 dark:bg-gray-800">
                        </div>
                        <textarea name="notes" placeholder="Notes" rows="2" class="w-full mt-3 rounded-md border-gray-300 dark:bg-gray-800"></textarea>
                        <button type="submit" class="mt-3 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-full text-sm">Add Destination</button>
                    </form>
                @endif

                <div class="space-y-4">
                    @foreach($destinations as $dest)
                        <div class="border-b border-gray-200 dark:border-gray-700 pb-3">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h4 class="font-bold">{{ $dest->city }}, {{ $dest->country }}</h4>
                                    <p class="text-sm text-gray-500">{{ $dest->arrival_date->format('M d') }} - {{ $dest->departure_date->format('M d') }}</p>
                                    <p class="text-sm">{{ $dest->notes }}</p>
                                    <div class="flex items-center gap-2 mt-2">
                                        <form action="{{ route('votes.store', [$trip, $dest]) }}" method="POST" class="inline">
                                            @csrf
                                            <input type="hidden" name="vote_type" value="up">
                                            <button type="submit" class="text-green-600 hover:text-green-800">👍 {{ $dest->votes()->where('vote_type','up')->count() }}</button>
                                        </form>
                                        <form action="{{ route('votes.store', [$trip, $dest]) }}" method="POST" class="inline">
                                            @csrf
                                            <input type="hidden" name="vote_type" value="down">
                                            <button type="submit" class="text-red-600 hover:text-red-800">👎 {{ $dest->votes()->where('vote_type','down')->count() }}</button>
                                        </form>
                                    </div>
                                </div>
                                @if($canEdit)
                                    <div class="flex gap-2">
                                        <a href="{{ route('destinations.edit', [$trip, $dest]) }}" class="text-amber-500">Edit</a>
                                        <form action="{{ route('destinations.destroy', [$trip, $dest]) }}" method="POST" onsubmit="return confirm('Delete destination?')">
                                            @csrf @method('DELETE')
                                            <button class="text-red-500">Delete</button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Right: Expenses -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-6">
                <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-4">💰 Expenses</h3>
                @if($canEdit)
                    <form action="{{ route('budgets.store', $trip) }}" method="POST" class="mb-6 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                        @csrf
                        <div class="grid grid-cols-2 gap-3">
                            <select name="category" required class="rounded-md border-gray-300 dark:bg-gray-800">
                                <option value="accommodation">Accommodation</option>
                                <option value="food">Food</option>
                                <option value="transport">Transport</option>
                                <option value="activities">Activities</option>
                                <option value="other">Other</option>
                            </select>
                            <input type="number" step="0.01" name="amount" placeholder="Amount" required class="rounded-md border-gray-300 dark:bg-gray-800">
                            <input type="date" name="expense_date" required class="rounded-md border-gray-300 dark:bg-gray-800">
                            <input type="text" name="description" placeholder="Description" class="rounded-md border-gray-300 dark:bg-gray-800">
                        </div>
                        <button type="submit" class="mt-3 bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-full text-sm">Add Expense</button>
                    </form>
                @endif

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="border-b dark:border-gray-700">
                                <th class="text-left py-2">Date</th>
                                <th>Category</th>
                                <th>Amount</th>
                                <th>Description</th>
                                @if($canEdit) <th></th> @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($budgets as $expense)
                                <tr class="border-b dark:border-gray-700">
                                    <td class="py-2">{{ $expense->expense_date->format('Y-m-d') }}</td>
                                    <td>{{ ucfirst($expense->category) }}</td>
                                    <td>${{ number_format($expense->amount, 2) }}</td>
                                    <td>{{ $expense->description ?? '-' }}</td>
                                    @if($canEdit)
                                        <td class="flex gap-2">
                                            <a href="{{ route('budgets.edit', [$trip, $expense]) }}" class="text-amber-500">Edit</a>
                                            <form action="{{ route('budgets.destroy', [$trip, $expense]) }}" method="POST" onsubmit="return confirm('Delete expense?')">
                                                @csrf @method('DELETE')
                                                <button class="text-red-500">Delete</button>
                                            </form>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <a href="{{ route('trips.index') }}" class="text-indigo-600 hover:underline">← Back to Trips</a>
        </div>
    </div>
</x-app-layout>