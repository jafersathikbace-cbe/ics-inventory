<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $material->name }}</h2>
                <p class="text-sm text-gray-500">
                    Unit: <b>{{ $material->unit?->name }}</b> ({{ $material->unit?->symbol }})
                    • Status:
                    @if($material->is_low)
                        <span class="px-2 py-1 rounded text-xs bg-red-100 text-red-800">LOW</span>
                    @else
                        <span class="px-2 py-1 rounded text-xs bg-green-100 text-green-800">OK</span>
                    @endif
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                @if($material->is_composite)
                    @can('materials.composite.manage')
                        <a href="{{ route('materials.components.index', $material) }}"
                           class="px-4 py-2 rounded bg-indigo-600 text-white hover:bg-indigo-700 text-sm">
                            Manage Components
                        </a>
                    @endcan
                @endif

                @can('materials.update')
                    <a href="{{ route('materials.edit', $material) }}" class="px-4 py-2 rounded bg-gray-100 hover:bg-gray-200 text-sm">
                        Edit
                    </a>
                @endcan

                <a href="{{ route('materials.index') }}" class="px-4 py-2 rounded bg-gray-100 hover:bg-gray-200 text-sm">
                    Back
                </a>
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

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left: image + core info -->
                <div class="bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="mb-4">
                            @if($material->image_path)
                                <img src="{{ asset('storage/'.$material->image_path) }}" class="w-full h-56 rounded object-cover border" alt="">
                            @else
                                <div class="w-full h-56 rounded bg-gray-100 border flex items-center justify-center text-gray-500">
                                    No image
                                </div>
                            @endif
                        </div>

                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Stock</span>
                                <span class="font-semibold">{{ $material->stock }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Threshold</span>
                                <span class="font-semibold">{{ $material->threshold }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Composite</span>
                                <span class="font-semibold">{{ $material->is_composite ? 'Yes' : 'No' }}</span>
                            </div>
                        </div>

                        @if($material->is_composite && $material->components->count() === 0)
                            <div class="mt-4 p-3 rounded bg-yellow-50 border border-yellow-200 text-yellow-800 text-sm">
                                This is composite but has <b>no components</b>. Click <b>Manage Components</b>.
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Middle/Right: stock add + composite preview -->
                <div class="bg-white shadow-sm sm:rounded-lg lg:col-span-2">
                    <div class="p-6">
                        <h3 class="font-semibold text-gray-800 mb-2">Add Stock</h3>
                        <p class="text-sm text-gray-500 mb-4">
                            Admin/Super Admin can increase stock. This action logs to stock history.
                        </p>

                        @can('materials.stock.add')
                            <form method="POST" action="{{ route('materials.stock.add', $material) }}" class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                @csrf

                                <div class="sm:col-span-1">
                                    <label class="block text-sm font-medium text-gray-700">Quantity to Add</label>
                                    <input name="qty" type="number" step="0.001" min="0.001"
                                           class="mt-1 w-full rounded border-gray-300" required>
                                </div>

                                <div class="sm:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700">Reason (optional)</label>
                                    <input name="reason" class="mt-1 w-full rounded border-gray-300" placeholder="Purchase / Adjustment / etc">
                                </div>

                                <div class="sm:col-span-3">
                                    <button class="px-4 py-2 rounded bg-indigo-600 text-white hover:bg-indigo-700">
                                        Add Stock
                                    </button>
                                </div>
                            </form>

                            @if($errors->any())
                                <div class="mt-4 p-3 rounded bg-red-50 text-red-800 border border-red-200">
                                    <ul class="list-disc pl-5">
                                        @foreach($errors->all() as $err)
                                            <li>{{ $err }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        @else
                            <div class="p-3 rounded bg-gray-50 text-gray-700 border">
                                You do not have permission to add stock.
                            </div>
                        @endcan

                        <div class="mt-8">
                            <h3 class="font-semibold text-gray-800 mb-2">Composite Breakdown Preview</h3>

                            @if(!$material->is_composite)
                                <p class="text-sm text-gray-500">This material is not composite.</p>
                            @else
                                @if($material->components->count() === 0)
                                    <p class="text-sm text-gray-500">
                                        No components yet. Go to <b>Manage Components</b>.
                                    </p>
                                @else
                                    <div class="border rounded p-4 bg-gray-50">
                                        @include('materials.partials.composite_tree', ['material' => $material, 'level' => 0])
                                    </div>
                                @endif
                            @endif
                        </div>

                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
