<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Profile</h2>
    </x-slot>

    @php
        $isAdminOrSuper = auth()->user()->hasAnyRole(['Admin', 'Super Admin']);
    @endphp

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg border">
                <div class="p-6">

                    @if(session('success'))
                        <div class="mb-4 p-3 rounded bg-green-50 text-green-800 border border-green-200">
                            {{ session('success') }}
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

                    <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
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

                        @if($isAdminOrSuper)
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Telegram Chat ID (optional)</label>
                                <input name="telegram_chat_id" value="{{ old('telegram_chat_id', $user->telegram_chat_id) }}" class="mt-1 w-full rounded border-gray-300">
                                <p class="text-xs text-gray-500 mt-1">Used for alerts and daily reports (Admin/Super Admin only).</p>
                            </div>
                        @else
                            <div class="p-3 rounded bg-gray-50 border text-gray-700 text-sm">
                                Telegram alerts are enabled only for <b>Admin</b> and <b>Super Admin</b>.
                            </div>
                        @endif

                        <div class="border rounded p-3">
                            <div class="text-sm font-medium text-gray-700">Change Password (optional)</div>

                            <label class="block text-xs text-gray-500 mt-2">New Password</label>
                            <input name="password" type="password" class="mt-1 w-full rounded border-gray-300">

                            <label class="block text-xs text-gray-500 mt-3">Confirm New Password</label>
                            <input name="password_confirmation" type="password" class="mt-1 w-full rounded border-gray-300">
                        </div>

                        <div class="flex items-center gap-3">
                            <button class="px-4 py-2 rounded bg-indigo-600 text-white hover:bg-indigo-700">
                                Update Profile
                            </button>
                            <a href="{{ route('dashboard') }}" class="px-4 py-2 rounded bg-gray-100 hover:bg-gray-200">
                                Back
                            </a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
