<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Trip Report - {{ $trip->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold">Expense Analytics</h3>
                        <a href="{{ route('reports.pdf', $trip) }}" class="bg-red-500 text-white px-3 py-1 rounded">Export PDF</a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div class="bg-blue-100 p-4 rounded text-center">
                            <h4 class="font-bold">Total Budget</h4>
                            <p class="text-2xl">${{ number_format($trip->total_budget, 2) }}</p>
                        </div>
                        <div class="bg-yellow-100 p-4 rounded text-center">
                            <h4 class="font-bold">Spent</h4>
                            <p class="text-2xl">${{ number_format($totalSpent, 2) }}</p>
                        </div>
                        <div class="bg-green-100 p-4 rounded text-center">
                            <h4 class="font-bold">Remaining</h4>
                            <p class="text-2xl">${{ number_format($remainingBudget, 2) }}</p>
                        </div>
                    </div>

                    <h3 class="font-semibold mb-2">Expenses by Category</h3>
                    <canvas id="expenseChart" class="mb-6" style="height: 300px;"></canvas>

                    <h3 class="font-semibold mb-2">Detailed Expenses</h3>
                    <table class="min-w-full border">
                        <thead>
                            <tr><th class="px-4 py-2 border">Date</th><th class="px-4 py-2 border">Category</th><th class="px-4 py-2 border">Amount</th><th class="px-4 py-2 border">Description</th></tr>
                        </thead>
                        <tbody>
                            @foreach($budgets as $expense)
                            <tr>
                                <td class="px-4 py-2 border">{{ $expense->expense_date->format('Y-m-d') }}</td>
                                <td class="px-4 py-2 border">{{ ucfirst($expense->category) }}</td>
                                <td class="px-4 py-2 border">${{ number_format($expense->amount, 2) }}</td>
                                <td class="px-4 py-2 border">{{ $expense->description ?? '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="mt-4">
                        <a href="{{ route('trips.show', $trip) }}" class="text-blue-500">← Back to Trip</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('expenseChart').getContext('2d');
        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: {!! json_encode($categories) !!},
                datasets: [{
                    data: {!! json_encode($amounts) !!},
                    backgroundColor: ['#3b82f6', '#ef4444', '#10b981', '#f59e0b', '#8b5cf6', '#ec489a']
                }]
            }
        });
    </script>
</x-app-layout>