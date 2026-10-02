<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Notifications</h2>
                <p class="text-sm text-gray-500">
                    Unread: <b>{{ $unreadCount }}</b>
                </p>
            </div>

            <form method="POST" action="{{ route('notifications.markAllRead') }}">
                @csrf
                <button class="px-4 py-2 rounded bg-gray-100 hover:bg-gray-200 text-sm">
                    Mark All Read
                </button>
            </form>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 p-3 rounded bg-green-50 text-green-800 border border-green-200">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 p-3 rounded bg-red-50 text-red-800 border border-red-200">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Filters -->
            <div class="mb-4 flex items-center gap-2">
                <a href="{{ route('notifications.index', ['filter' => 'all']) }}"
                   class="px-3 py-1 rounded text-sm border
                   {{ $filter === 'all' ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-gray-700 border-gray-200 hover:bg-gray-50' }}">
                    All
                </a>

                <a href="{{ route('notifications.index', ['filter' => 'unread']) }}"
                   class="px-3 py-1 rounded text-sm border
                   {{ $filter === 'unread' ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-gray-700 border-gray-200 hover:bg-gray-50' }}">
                    Unread
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border">
                <div class="p-6">

                    @if($notifications->count() === 0)
                        <div class="p-3 rounded bg-gray-50 border text-gray-700">
                            No notifications.
                        </div>
                    @else
                        <div class="space-y-3">
                            @foreach($notifications as $n)
                                <div class="border rounded p-4 {{ $n->read_at ? 'bg-white' : 'bg-yellow-50' }}">
                                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <div class="font-semibold text-gray-800">{{ $n->title }}</div>
                                                @if(!$n->read_at)
                                                    <span class="text-xs px-2 py-1 rounded bg-yellow-200 text-yellow-900">Unread</span>
                                                @endif
                                            </div>

                                            <div class="text-sm text-gray-700 mt-1 whitespace-pre-line">{{ $n->message }}</div>

                                            <div class="text-xs text-gray-500 mt-2">
                                                {{ $n->created_at?->format('Y-m-d H:i') }}
                                                @if($n->read_at)
                                                    • Read at {{ $n->read_at?->format('Y-m-d H:i') }}
                                                @endif
                                            </div>
                                        </div>

                                        <div class="flex items-center gap-2">
                                            @if(!$n->read_at)
                                                <form method="POST" action="{{ route('notifications.markRead', $n) }}">
                                                    @csrf
                                                    <input type="hidden" name="filter" value="{{ $filter }}">
                                                    <button class="px-3 py-1 rounded bg-gray-100 hover:bg-gray-200 text-sm">
                                                        Mark Read
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-4">
                            {{ $notifications->links() }}
                        </div>
                    @endif

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
