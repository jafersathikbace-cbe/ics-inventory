<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Create Unit</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if($errors->any())
                        <div class="mb-4 p-3 rounded bg-red-50 text-red-800 border border-red-200">
                            <ul class="list-disc pl-5">
                                @foreach($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('units.store') }}" class="space-y-4">
                        @csrf

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Name</label>
                            <input name="name" value="{{ old('name') }}" class="mt-1 w-full rounded border-gray-300" placeholder="feet" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Symbol</label>
                            <input name="symbol" value="{{ old('symbol') }}" class="mt-1 w-full rounded border-gray-300" placeholder="ft" required>
                        </div>

                        <div class="flex items-center gap-2">
                            <input id="allow_decimal" name="allow_decimal" type="checkbox" value="1" class="rounded border-gray-300"
                                   {{ old('allow_decimal') ? 'checked' : '' }}>
                            <label for="allow_decimal" class="text-sm text-gray-700">Allow decimal quantities</label>
                        </div>

                        <div class="flex items-center gap-3">
                            <button class="px-4 py-2 rounded bg-indigo-600 text-white hover:bg-indigo-700">
                                Save
                            </button>
                            <a href="{{ route('units.index') }}" class="px-4 py-2 rounded bg-gray-100 hover:bg-gray-200">
                                Cancel
                            </a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
