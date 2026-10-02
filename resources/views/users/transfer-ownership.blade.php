<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Transfer Ownership</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg border">
                <div class="p-6">

                    @if(session('error'))
                        <div class="mb-4 p-3 rounded bg-red-50 text-red-800 border border-red-200">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="mb-4 p-3 rounded bg-green-50 text-green-800 border border-green-200">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="mb-4 text-sm text-gray-700">
                        Current owner: <b>{{ $owner?->name ?? '-' }}</b>
                    </div>

                    <div class="mb-4 text-sm text-gray-500">
                        Ownership is defined as the <b>first Super Admin</b>. Transfer changes which Super Admin is earliest.
                    </div>

                    @if(!$owner)
                        <div class="p-3 rounded bg-red-50 border text-red-800">
                            Owner not found.
                        </div>
                    @elseif($targets->count() === 0)
                        <div class="p-3 rounded bg-yellow-50 border text-yellow-800">
                            No other Super Admin exists. Create another Super Admin first.
                        </div>
                    @else
                        <form method="POST" action="{{ route('users.transferOwnership') }}" class="space-y-4">
                            @csrf

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Transfer to Super Admin</label>
                                <select name="target_user_id" class="mt-1 w-full rounded border-gray-300" required>
                                    <option value="">Select user</option>
                                    @foreach($targets as $t)
                                        <option value="{{ $t->id }}">{{ $t->name }} ({{ $t->email }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <button class="px-4 py-2 rounded bg-indigo-600 text-white hover:bg-indigo-700">
                                Transfer Ownership
                            </button>

                            <a href="{{ route('users.index') }}" class="ml-2 px-4 py-2 rounded bg-gray-100 hover:bg-gray-200">
                                Cancel
                            </a>
                        </form>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
