<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Product</h2>
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

                    <form method="POST" action="{{ route('products.update', $product) }}" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Name</label>
                            <input name="name" value="{{ old('name', $product->name) }}" class="mt-1 w-full rounded border-gray-300" required>
                        </div>

                        <div class="border rounded p-3">
                            <div class="text-sm font-medium text-gray-700 mb-2">Current Image</div>
                            @if($product->image_path)
                                <img src="{{ asset('storage/'.$product->image_path) }}" class="w-28 h-28 rounded object-cover border" alt="">
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
                            <a href="{{ route('products.show', $product) }}" class="px-4 py-2 rounded bg-gray-100 hover:bg-gray-200">
                                Cancel
                            </a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
