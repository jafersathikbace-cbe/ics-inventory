<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Composite: {{ $material->name }}
                </h2>
                <p class="text-sm text-gray-500">
                    Define which child materials are required to produce <b>1 {{ $material->unit?->symbol }}</b> of this composite.
                </p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('materials.show', $material) }}"
                   class="px-4 py-2 rounded bg-gray-100 hover:bg-gray-200 text-sm">
                    Back to Material
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

            @if($errors->any())
                <div class="mb-4 p-3 rounded bg-red-50 text-red-800 border border-red-200">
                    <ul class="list-disc pl-5">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Left: Add/Update component -->
                <div class="bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="font-semibold text-gray-800 mb-2">Add / Update Component</h3>
                        <p class="text-sm text-gray-500 mb-4">
                            Select a child raw material and set required quantity for producing <b>1</b> unit of the parent.
                        </p>

                        <form method="POST" action="{{ route('materials.components.store', $material) }}" class="space-y-4">
                            @csrf

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Child Material</label>
                                <select name="child_material_id" class="mt-1 w-full rounded border-gray-300" required>
                                    <option value="">Select child material</option>
                                    @foreach($childOptions as $opt)
                                        <option value="{{ $opt->id }}">
                                            {{ $opt->name }} ({{ $opt->unit?->symbol }})
                                        </option>
                                    @endforeach
                                </select>
                                <p class="text-xs text-gray-500 mt-1">
                                    (Composite children disabled in this version to avoid cycles.)
                                </p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">
                                    Qty required for 1 {{ $material->unit?->symbol }}
                                </label>
                                <input name="qty_per_parent" type="number" step="0.001" min="0.001"
                                       class="mt-1 w-full rounded border-gray-300" required>
                                <p class="text-xs text-gray-500 mt-1">
                                    Example: Trays (1 nos) needs Aluminium L angle 3/4 = 4 sqft, stainless net = 1 sqft
                                </p>
                            </div>

                            <button class="px-4 py-2 rounded bg-indigo-600 text-white hover:bg-indigo-700">
                                Save Component
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Right: Current components table -->
                <div class="bg-white shadow-sm sm:rounded-lg lg:col-span-2">
                    <div class="p-6">
                        <h3 class="font-semibold text-gray-800 mb-2">Current Breakdown</h3>

                        @if($material->components->count() === 0)
                            <div class="p-3 rounded bg-gray-50 border text-gray-700">
                                No components yet.
                            </div>
                        @else
                            <div class="overflow-x-auto">
                                <table class="min-w-full text-sm">
                                    <thead>
                                    <tr class="text-left text-gray-600 border-b">
                                        <th class="py-2">Child Material</th>
                                        <th class="py-2">Qty per 1</th>
                                        <th class="py-2">Unit</th>
                                        <th class="py-2 text-right">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($material->components as $c)
                                        <tr class="border-b">
                                            <td class="py-3">{{ $c->childMaterial?->name }}</td>
                                            <td class="py-3">{{ $c->qty_per_parent }}</td>
                                            <td class="py-3">{{ $c->childMaterial?->unit?->symbol }}</td>
                                            <td class="py-3 text-right">
                                                <form method="POST"
                                                      action="{{ route('materials.components.destroy', [$material, $c]) }}"
                                                      onsubmit="return confirm('Remove this component?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="px-3 py-1 rounded bg-red-600 text-white hover:bg-red-700">
                                                        Remove
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif

                        <div class="mt-6">
                            <h3 class="font-semibold text-gray-800 mb-2">Nested View</h3>
                            <p class="text-sm text-gray-500 mb-3">
                                A clear breakdown view. If composites inside composites are enabled later, this view will still work.
                            </p>

                            <div class="border rounded p-4 bg-gray-50">
                                @include('materials.partials.composite_tree', ['material' => $material, 'level' => 0])
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
