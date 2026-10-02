<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">User Management</h2>
                <p class="text-sm text-gray-500">
                    Owner: <b>{{ $owner?->name ?? '-' }}</b> (first Super Admin)
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                @can('users.ownership.transfer')
                    <a href="{{ route('users.transferOwnershipForm') }}"
                       class="px-4 py-2 rounded bg-gray-100 hover:bg-gray-200 text-sm">
                        Transfer Ownership
                    </a>
                @endcan

                @can('users.create')
                    <a href="{{ route('users.create') }}"
                       class="px-4 py-2 rounded bg-indigo-600 text-white hover:bg-indigo-700 text-sm">
                        + Create User
                    </a>
                @endcan
            </div>
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

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border">
                <div class="p-6">

                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr class="text-left text-gray-600 border-b">
                                    <th class="py-2">Name</th>
                                    <th class="py-2">Email</th>
                                    <th class="py-2">Role</th>
                                    <th class="py-2">Telegram Chat ID</th>
                                    <th class="py-2">Owner</th>
                                    <th class="py-2 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $u)
                                    @php
                                        $role = $u->getRoleNames()->first() ?? '-';
                                        $isOwner = $owner && ((int)$owner->id === (int)$u->id);
                                    @endphp
                                    <tr class="border-b">
                                        <td class="py-3 font-medium">{{ $u->name }}</td>
                                        <td class="py-3">{{ $u->email }}</td>
                                        <td class="py-3">
                                            <span class="px-2 py-1 rounded text-xs bg-gray-100 text-gray-800">
                                                {{ $role }}
                                            </span>
                                        </td>
                                        <td class="py-3">{{ $u->telegram_chat_id ?? '-' }}</td>
                                        <td class="py-3">
                                            @if($isOwner)
                                                <span class="px-2 py-1 rounded text-xs bg-indigo-100 text-indigo-800">Owner</span>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="py-3 text-right">
                                            <div class="inline-flex items-center gap-2">
                                                @can('users.update')
                                                    <a href="{{ route('users.edit', $u) }}"
                                                       class="px-3 py-1 rounded bg-gray-100 hover:bg-gray-200">
                                                        Edit
                                                    </a>
                                                @endcan

                                                @can('users.delete')
                                                    <form method="POST" action="{{ route('users.destroy', $u) }}"
                                                          onsubmit="return confirm('Delete this user?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="px-3 py-1 rounded bg-red-600 text-white hover:bg-red-700">
                                                            Delete
                                                        </button>
                                                    </form>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $users->links() }}
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
