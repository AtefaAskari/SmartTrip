<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold gradient-text">Share Trip: {{ $trip->name }}</h2>
    </x-slot>

    <div class="py-12 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-6">
            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded mb-4">{{ session('error') }}</div>
            @endif
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded mb-4">{{ session('success') }}</div>
            @endif

            <h3 class="text-xl font-bold mb-4">Invite Someone</h3>
            <form action="{{ route('trips.share.store', $trip) }}" method="POST" class="mb-8">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="md:col-span-1">
                        <input type="email" name="email" placeholder="User's email address" required class="w-full rounded-md border-gray-300 dark:bg-gray-700">
                    </div>
                    <div>
                        <select name="permission" class="w-full rounded-md border-gray-300 dark:bg-gray-700">
                            <option value="view">View only</option>
                            <option value="edit">Can edit</option>
                        </select>
                    </div>
                    <div>
                        <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md transition">Send Invitation</button>
                    </div>
                </div>
                @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </form>

            <h3 class="text-xl font-bold mb-4">People with Access</h3>
            @if($trip->sharedWithUsers->count())
                <div class="overflow-x-auto">
                    <table class="min-w-full border">
                        <thead>
                            <tr><th class="px-4 py-2 border">Name</th><th class="px-4 py-2 border">Email</th><th class="px-4 py-2 border">Permission</th><th class="px-4 py-2 border">Action</th></tr>
                        </thead>
                        <tbody>
                            @foreach($trip->sharedWithUsers as $user)
                            <tr>
                                <td class="px-4 py-2 border">{{ $user->name }}</td>
                                <td class="px-4 py-2 border">{{ $user->email }}</td>
                                <td class="px-4 py-2 border">{{ ucfirst($user->pivot->permission) }}</td>
                                <td class="px-4 py-2 border">
                                    <form action="{{ route('trips.share.destroy', [$trip, $user]) }}" method="POST" onsubmit="return confirm('Remove access?')">
                                        @csrf @method('DELETE')
                                        <button class="text-red-500">Remove</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-500">No users have access yet.</p>
            @endif

            <div class="mt-6">
                <a href="{{ route('trips.show', $trip) }}" class="text-indigo-600 hover:underline">← Back to Trip</a>
            </div>
        </div>
    </div>
</x-app-layout>