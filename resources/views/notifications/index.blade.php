<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Notifications
            @if($unreadCount > 0)
                <span class="ml-2 bg-red-500 text-white text-xs px-2 py-1 rounded">{{ $unreadCount }} new</span>
            @endif
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success')) <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div> @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if($notifications->count())
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-bold">Your Alerts</h3>
                            <form action="{{ route('notifications.mark-all-read') }}" method="POST">
                                @csrf
                                <button class="text-blue-500 text-sm">Mark all as read</button>
                            </form>
                        </div>
                        <div class="space-y-3">
                            @foreach($notifications as $notif)
                                <div class="border rounded p-3 flex justify-between items-start {{ $notif->is_read ? 'bg-white' : 'bg-blue-50' }}">
                                    <div>
                                        <div class="font-semibold">{{ $notif->title }}</div>
                                        <div class="text-sm text-gray-600">{{ $notif->message }}</div>
                                        <div class="text-xs text-gray-400 mt-1">{{ $notif->created_at->diffForHumans() }}</div>
                                    </div>
                                    <div class="flex gap-2">
                                        @if(!$notif->is_read)
                                            <form action="{{ route('notifications.mark-read', $notif) }}" method="POST">
                                                @csrf
                                                <button class="text-green-500 text-sm">Mark read</button>
                                            </form>
                                        @endif
                                        <form action="{{ route('notifications.destroy', $notif) }}" method="POST" onsubmit="return confirm('Delete this notification?')">
                                            @csrf @method('DELETE')
                                            <button class="text-red-500 text-sm">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-4">{{ $notifications->links() }}</div>
                    @else
                        <p class="text-gray-500">No notifications.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>