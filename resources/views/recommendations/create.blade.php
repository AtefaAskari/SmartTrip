<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Get AI-Powered Travel Recommendations
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('recommendations.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-1">Which country? *</label>
                            <input type="text" name="country" required value="{{ old('country') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring" placeholder="e.g., Japan, France, Italy">
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-1">Total budget (USD) *</label>
                            <input type="number" step="100" name="budget" required value="{{ old('budget') }}" class="w-full rounded-md border-gray-300">
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-1">Interests (comma separated) *</label>
                            <input type="text" name="interests" required value="{{ old('interests') }}" class="w-full rounded-md border-gray-300" placeholder="culture, nature, adventure, nightlife, shopping">
                            <p class="text-xs text-gray-500 mt-1">e.g., culture, nature, food, adventure</p>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-full shadow transition">Get Recommendations ✨</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>