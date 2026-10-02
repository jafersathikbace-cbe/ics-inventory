<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    BOM: {{ $product->name }}
                </h2>
                <p class="text-sm text-gray-500">
                    Add materials required to produce <b>1 unit</b> of product.
                </p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('products.show', $product) }}"
                   class="px-4 py-2 rounded bg-gray-100 hover:bg-gray-200 text-sm">
                    Back to Product
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

                <!-- Left: Add / update BOM row -->
                <div class="bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="font-semibold text-gray-800 mb-2">Add / Update Material</h3>
                        <p class="text-sm text-gray-500 mb-4">
                            Select a material and set required quantity for producing <b>1</b> unit of this product.
                        </p>

                        <form method="POST" action="{{ route('products.bom.store', $product) }}" class="space-y-4">
                            @csrf

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Material</label>
                                <select name="material_id" class="mt-1 w-full rounded border-gray-300" required>
                                    <option value="">Select material</option>
                                    @foreach($allMaterials as $m)
                                        <option value="{{ $m->id }}">
                                            {{ $m->name }} ({{ $m->unit?->symbol }}) {{ $m->is_composite ? ' - Composite' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Qty per 1 product</label>
                                <input name="qty_per_product" type="number" step="0.001" min="0.001"
                                       class="mt-1 w-full rounded border-gray-300" required>
                            </div>

                            <button class="px-4 py-2 rounded bg-indigo-600 text-white hover:bg-indigo-700">
                                Save BOM Item
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Right: Current BOM -->
                <div class="bg-white shadow-sm sm:rounded-lg lg:col-span-2">
                    <div class="p-6">
                        <h3 class="font-semibold text-gray-800 mb-2">Current BOM</h3>

                        @if($product->materials->count() === 0)
                            <div class="p-3 rounded bg-gray-50 border text-gray-700">
                                No BOM items yet.
                            </div>
                        @else
                            <div class="overflow-x-auto">
                                <table class="min-w-full text-sm">
                                    <thead>
                                        <tr class="text-left text-gray-600 border-b">
                                            <th class="py-2">Material</th>
                                            <th class="py-2">Qty per 1</th>
                                            <th class="py-2">Unit</th>
                                            <th class="py-2">Type</th>
                                            <th class="py-2 text-right">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($product->materials as $m)
                                            <tr class="border-b">
                                                <td class="py-3">{{ $m->name }}</td>
                                                <td class="py-3">{{ $m->pivot->qty_per_product }}</td>
                                                <td class="py-3">{{ $m->unit?->symbol }}</td>
                                                <td class="py-3">
                                                    @if($m->is_composite)
                                                        <span class="px-2 py-1 rounded text-xs bg-yellow-100 text-yellow-800">Composite</span>
                                                    @else
                                                        <span class="px-2 py-1 rounded text-xs bg-gray-100 text-gray-800">Raw</span>
                                                    @endif
                                                </td>
                                                <td class="py-3 text-right">
                                                    <form method="POST"
                                                          action="{{ route('products.bom.destroy', [$product, $m->id]) }}"
                                                          onsubmit="return confirm('Remove this BOM item?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="px-3 py-1 rounded bg-red-600 text-white hover:bg-red-700">
                                                            Remove
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>

                                            @if($m->is_composite)
                                                <tr class="border-b bg-gray-50">
                                                    <td colspan="5" class="py-3 px-3">
                                                        <div class="text-sm text-gray-700">
                                                            <span class="font-semibold">Composite breakdown:</span>
                                                            @if($m->components->count() === 0)
                                                                <span class="text-red-700">No components set!</span>
                                                            @else
                                                                <ul class="list-disc pl-6">
                                                                    @foreach($m->components as $c)
                                                                        <li>
                                                                            {{ $c->childMaterial?->name }}
                                                                            — per 1 {{ $m->unit?->symbol }}:
                                                                            <b>{{ $c->qty_per_parent }}</b> {{ $c->childMaterial?->unit?->symbol }}
                                                                            → per 1 product:
                                                                            <b>{{ round(((float)$m->pivot->qty_per_product) * ((float)$c->qty_per_parent), 3) }}</b>
                                                                            {{ $c->childMaterial?->unit?->symbol }}
                                                                        </li>
                                                                    @endforeach
                                                                </ul>
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif

                        <div class="mt-8">
                            <h3 class="font-semibold text-gray-800 mb-2">Expanded Requirements Preview</h3>
                            <p class="text-sm text-gray-500 mb-3">
                                This preview flattens composite materials into raw materials. This is what Phase 7 uses for stock check/deduction.
                            </p>

                            @if(count($expanded) === 0)
                                <div class="p-3 rounded bg-gray-50 border text-gray-700">
                                    No expanded requirements yet.
                                </div>
                            @else
                                <div class="overflow-x-auto">
                                    <table class="min-w-full text-sm">
                                        <thead>
                                            <tr class="text-left text-gray-600 border-b">
                                                <th class="py-2">Material</th>
                                                <th class="py-2">Total Qty per 1 product</th>
                                                <th class="py-2">Unit</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($expanded as $row)
                                                <tr class="border-b">
                                                    <td class="py-3">{{ $row['name'] }}</td>
                                                    <td class="py-3 font-semibold">{{ $row['qty'] }}</td>
                                                    <td class="py-3">{{ $row['unit'] }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>

                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
