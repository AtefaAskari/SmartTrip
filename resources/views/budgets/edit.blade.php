<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Expense
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('budgets.update', [$trip, $budget]) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-1">Category *</label>
                            <select name="category" required class="w-full rounded-md border-gray-300">
                                <option value="accommodation" {{ old('category', $budget->category) == 'accommodation' ? 'selected' : '' }}>Accommodation</option>
                                <option value="food" {{ old('category', $budget->category) == 'food' ? 'selected' : '' }}>Food</option>
                                <option value="transport" {{ old('category', $budget->category) == 'transport' ? 'selected' : '' }}>Transport</option>
                                <option value="activities" {{ old('category', $budget->category) == 'activities' ? 'selected' : '' }}>Activities</option>
                                <option value="other" {{ old('category', $budget->category) == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('category') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-1">Amount ($) *</label>
                            <input type="number" step="0.01" name="amount" value="{{ old('amount', $budget->amount) }}" required class="w-full rounded-md border-gray-300">
                            @error('amount') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-1">Expense Date *</label>
                            <input type="date" name="expense_date" value="{{ old('expense_date', $budget->expense_date->format('Y-m-d')) }}" required class="w-full rounded-md border-gray-300">
                            @error('expense_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-1">Description</label>
                            <textarea name="description" rows="2" class="w-full rounded-md border-gray-300">{{ old('description', $budget->description) }}</textarea>
                        </div>

                        <div class="flex justify-end gap-2">
                            <a href="{{ route('trips.show', $trip) }}" class="bg-gray-500 text-white px-4 py-2 rounded">Cancel</a>
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Update Expense</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>