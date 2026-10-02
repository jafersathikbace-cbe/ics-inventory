<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Products</h2>

            @can('products.create')
                <a href="{{ route('products.create') }}"
                   class="px-4 py-2 rounded bg-indigo-600 text-white text-sm hover:bg-indigo-700">
                    + Add Product
                </a>
            @endcan
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

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($products as $p)
                    <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden border">
                        <div class="p-4">
                            @if($p->image_path)
                                <img src="{{ asset('storage/'.$p->image_path) }}" class="w-full h-44 rounded object-cover border" alt="">
                            @else
                                <div class="w-full h-44 rounded bg-gray-100 border flex items-center justify-center text-gray-500">
                                    No image
                                </div>
                            @endif

                            <div class="mt-3">
                                <a href="{{ route('products.show', $p) }}" class="font-semibold text-gray-800 hover:underline">
                                    {{ $p->name }}
                                </a>
                            </div>

                            <div class="mt-4 flex items-center justify-between">
                                <a href="{{ route('products.show', $p) }}"
                                   class="px-3 py-1 rounded bg-gray-100 hover:bg-gray-200 text-sm">
                                    View
                                </a>

                                <div class="flex items-center gap-2">
                                    @can('products.update')
                                        <a href="{{ route('products.edit', $p) }}"
                                           class="px-3 py-1 rounded bg-gray-100 hover:bg-gray-200 text-sm">
                                            Edit
                                        </a>
                                    @endcan

                                    @can('products.delete')
                                        <form action="{{ route('products.destroy', $p) }}" method="POST"
                                              onsubmit="return confirm('Delete this product?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="px-3 py-1 rounded bg-red-600 text-white hover:bg-red-700 text-sm">
                                                Delete
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center text-gray-500">
                        No products yet. Add products and upload images.
                    </div>
                @endforelse
            </div>

            <div class="mt-6">
                {{ $products->links() }}
            </div>

        </div>
    </div>
</x-app-layout>
