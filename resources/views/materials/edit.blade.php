<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Material</h2>
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

                    <form method="POST" action="{{ route('materials.update', $material) }}" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Name</label>
                            <input name="name" value="{{ old('name', $material->name) }}" class="mt-1 w-full rounded border-gray-300" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Unit</label>
                            <select name="unit_id" class="mt-1 w-full rounded border-gray-300" required>
                                @foreach($units as $u)
                                    <option value="{{ $u->id }}" {{ old('unit_id', $material->unit_id) == $u->id ? 'selected' : '' }}>
                                        {{ $u->name }} ({{ $u->symbol }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Stock</label>
                                <input name="stock" type="number" step="0.001" min="0" value="{{ old('stock', $material->stock) }}" class="mt-1 w-full rounded border-gray-300" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Threshold</label>
                                <input name="threshold" type="number" step="0.001" min="0" value="{{ old('threshold', $material->threshold) }}" class="mt-1 w-full rounded border-gray-300" required>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <input id="is_composite" name="is_composite" type="checkbox" value="1" class="rounded border-gray-300"
                                   {{ old('is_composite', $material->is_composite) ? 'checked' : '' }}>
                            <label for="is_composite" class="text-sm text-gray-700">
                                This material is composite
                            </label>
                        </div>

                        <div class="border rounded p-3">
                            <div class="text-sm font-medium text-gray-700 mb-2">Current Image</div>
                            @if($material->image_path)
                                <img src="{{ asset('storage/'.$material->image_path) }}" class="w-28 h-28 rounded object-cover border" alt="">
                            @else
                                <div class="text-sm text-gray-500">No image</div>
                            @endif

                            <div class="mt-3">
                                <label class="block text-sm font-medium text-gray-700">Replace Image (optional)</label>
                                <input name="image" type="file" accept="image/*" class="mt-1 w-full">
                                <p class="text-xs text-gray-500 mt-1">Allowed: jpg, png, webp (max 4MB)</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <button class="px-4 py-2 rounded bg-indigo-600 text-white hover:bg-indigo-700">
                                Update
                            </button>
                            <a href="{{ route('materials.show', $material) }}" class="px-4 py-2 rounded bg-gray-100 hover:bg-gray-200">
                                Cancel
                            </a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
