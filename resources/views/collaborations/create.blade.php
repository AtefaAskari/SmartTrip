<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Invite Collaborator - {{ $trip->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('collaborations.store', $trip) }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-1">User Email *</label>
                            <input type="email" name="email" required value="{{ old('email') }}" placeholder="friend@example.com" class="w-full rounded-md border-gray-300">
                            @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-1">Role *</label>
                            <select name="role" class="w-full rounded-md border-gray-300">
                                <option value="viewer">Viewer (can only view)</option>
                                <option value="editor">Editor (can add/edit itinerary, expenses, gallery)</option>
                                <option value="admin">Admin (full control except delete trip)</option>
                            </select>
                        </div>

                        <div class="flex justify-end gap-2">
                            <a href="{{ route('collaborations.index', $trip) }}" class="bg-gray-500 text-white px-4 py-2 rounded">Cancel</a>
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Send Invitation</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>