<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Collaborators - {{ $trip->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success')) <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div> @endif
            @if(session('error')) <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">{{ session('error') }}</div> @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold">People with Access</h3>
                        <a href="{{ route('collaborations.create', $trip) }}" class="bg-blue-500 text-white px-3 py-1 rounded">Invite User</a>
                    </div>

                    <table class="min-w-full border">
                        <thead>
                            <tr><th class="px-4 py-2 border">Name</th><th class="px-4 py-2 border">Email</th><th class="px-4 py-2 border">Role</th><th class="px-4 py-2 border">Status</th><th class="px-4 py-2 border">Actions</th></tr>
                        </thead>
                        <tbody>
                            <tr><td colspan="5" class="px-4 py-2 border bg-gray-100">Owner: {{ $trip->user->name }} (Admin)</td></tr>
                            @foreach($collaborators as $collab)
                                <tr>
                                    <td class="px-4 py-2 border">{{ $collab->user->name }}</td>
                                    <td class="px-4 py-2 border">{{ $collab->user->email }}</td>
                                    <td class="px-4 py-2 border">{{ ucfirst($collab->role) }}</td>
                                    <td class="px-4 py-2 border">
                                        <span class="px-2 py-1 rounded text-xs {{ $collab->status === 'accepted' ? 'bg-green-200 text-green-800' : ($collab->status === 'pending' ? 'bg-yellow-200 text-yellow-800' : 'bg-red-200 text-red-800') }}">
                                            {{ ucfirst($collab->status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2 border">
                                        <form action="{{ route('collaborations.destroy', [$trip, $collab]) }}" method="POST" onsubmit="return confirm('Remove this user?')">
                                            @csrf @method('DELETE')
                                            <button class="text-red-500">Remove</button>
                                        </form>
                                    </td>
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
</x-app-layout>