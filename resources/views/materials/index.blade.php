<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Materials</h2>
                <p class="text-sm text-gray-500">Low stock: <span class="font-semibold">{{ $lowCount }}</span></p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('materials.index') }}"
                   class="px-4 py-2 rounded text-sm {{ request('filter') !== 'low' ? 'bg-indigo-600 text-white' : 'bg-gray-100 hover:bg-gray-200' }}">
                    All
                </a>
                <a href="{{ route('materials.index', ['filter' => 'low']) }}"
                   class="px-4 py-2 rounded text-sm {{ request('filter') === 'low' ? 'bg-indigo-600 text-white' : 'bg-gray-100 hover:bg-gray-200' }}">
                    Low Stock
                </a>

                @can('materials.create')
                    <a href="{{ route('materials.create') }}"
                       class="px-4 py-2 rounded bg-indigo-600 text-white text-sm hover:bg-indigo-700">
                        + Add Material
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

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr class="text-left text-gray-600 border-b">
                                    <th class="py-2">Image</th>
                                    <th class="py-2">Name</th>
                                    <th class="py-2">Unit</th>
                                    <th class="py-2">Stock</th>
                                    <th class="py-2">Threshold</th>
                                    <th class="py-2">Status</th>
                                    <th class="py-2 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($materials as $m)
                                    <tr class="border-b">
                                        <td class="py-3">
                                            @if($m->image_path)
                                                <img src="{{ asset('storage/'.$m->image_path) }}" class="w-12 h-12 rounded object-cover border" alt="">
                                            @else
                                                <div class="w-12 h-12 rounded bg-gray-100 border flex items-center justify-center text-xs text-gray-500">No Image</div>
                                            @endif
                                        </td>
                                        <td class="py-3">
                                            <a href="{{ route('materials.show', $m) }}" class="text-indigo-700 hover:underline font-medium">
                                                {{ $m->name }}
                                            </a>
                                            @if($m->is_composite)
                                                <div class="text-xs text-gray-500">Composite</div>
                                            @endif
                                        </td>
                                        <td class="py-3">{{ $m->unit?->symbol }}</td>
                                        <td class="py-3">{{ $m->stock }}</td>
                                        <td class="py-3">{{ $m->threshold }}</td>
                                        <td class="py-3">
                                            @if($m->is_low)
                                                <span class="px-2 py-1 rounded text-xs bg-red-100 text-red-800">LOW</span>
                                            @else
                                                <span class="px-2 py-1 rounded text-xs bg-green-100 text-green-800">OK</span>
                                            @endif
                                        </td>
                                        <td class="py-3 text-right space-x-2">
                                            <a href="{{ route('materials.show', $m) }}"
                                               class="px-3 py-1 rounded bg-gray-100 hover:bg-gray-200">
                                                View
                                            </a>

                                            @can('materials.update')
                                                <a href="{{ route('materials.edit', $m) }}"
                                                   class="px-3 py-1 rounded bg-gray-100 hover:bg-gray-200">
                                                    Edit
                                                </a>
                                            @endcan

                                            @can('materials.delete')
                                                <form action="{{ route('materials.destroy', $m) }}" method="POST" class="inline"
                                                      onsubmit="return confirm('Delete this material?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="px-3 py-1 rounded bg-red-600 text-white hover:bg-red-700">
                                                        Delete
                                                    </button>
                                                </form>
                                            @endcan
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="py-6 text-center text-gray-500">
                                            No materials yet. Add materials and upload images.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $materials->links() }}
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
