<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit User</h2>
    </x-slot>

    @php
        $currentRole = old('role', $user->getRoleNames()->first() ?? 'Operator');
    @endphp

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg border">
                <div class="p-6">

                    @if(session('error'))
                        <div class="mb-4 p-3 rounded bg-red-50 text-red-800 border border-red-200">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="mb-4 p-3 rounded bg-red-50 text-red-800 border border-red-200">
                            <ul class="list-disc pl-5">
                                @foreach($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('users.update', $user) }}" class="space-y-4"
                          x-data="{ role: '{{ $currentRole }}' }">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Name</label>
                            <input name="name" value="{{ old('name', $user->name) }}" class="mt-1 w-full rounded border-gray-300" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email</label>
                            <input name="email" type="email" value="{{ old('email', $user->email) }}" class="mt-1 w-full rounded border-gray-300" required>
                        </div>

                        <div class="border rounded p-3">
                            <div class="text-sm font-medium text-gray-700">Password (optional)</div>
                            <input name="password" type="password" class="mt-1 w-full rounded border-gray-300">
                            <p class="text-xs text-gray-500 mt-1">Leave blank to keep current password.</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Role</label>
                            <select name="role" class="mt-1 w-full rounded border-gray-300" required x-model="role">
                                @foreach($roles as $r)
                                    <option value="{{ $r }}"
                                        {{ (old('role', $user->getRoleNames()->first() ?? '') === $r) ? 'selected' : '' }}>
                                        {{ $r }}
                                    </option>
                                @endforeach
                            </select>
                            @if($isOwner)
                                <p class="text-xs text-indigo-700 mt-1">
                                    This user is Owner. Role cannot be changed from Super Admin unless ownership is transferred.
                                </p>
                            @endif
                        </div>

                        <template x-if="role === 'Admin' || role === 'Super Admin'">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Telegram Chat ID (optional)</label>
                                <input name="telegram_chat_id" value="{{ old('telegram_chat_id', $user->telegram_chat_id) }}" class="mt-1 w-full rounded border-gray-300">
                                <p class="text-xs text-gray-500 mt-1">Only Admin/Super Admin receive alerts & reports.</p>
                            </div>
                        </template>

                        <template x-if="role === 'Operator'">
                            <div class="p-3 rounded bg-gray-50 border text-gray-700 text-sm">
                                Operators do not receive Telegram alerts. Telegram Chat ID will be cleared automatically.
                            </div>
                        </template>

                        <div class="flex items-center gap-3">
                            <button class="px-4 py-2 rounded bg-indigo-600 text-white hover:bg-indigo-700">
                                Update
                            </button>
                            <a href="{{ route('users.index') }}" class="px-4 py-2 rounded bg-gray-100 hover:bg-gray-200">
                                Cancel
                            </a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
