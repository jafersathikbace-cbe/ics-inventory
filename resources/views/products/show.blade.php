<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $product->name }}</h2>
                <p class="text-sm text-gray-500">
                    BOM items: <b>{{ $product->materials->count() }}</b>
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                @can('products.bom.manage')
                    <a href="{{ route('products.bom.index', $product) }}"
                       class="px-4 py-2 rounded bg-indigo-600 text-white hover:bg-indigo-700 text-sm">
                        Manage BOM
                    </a>
                @endcan

                @can('products.update')
                    <a href="{{ route('products.edit', $product) }}" class="px-4 py-2 rounded bg-gray-100 hover:bg-gray-200 text-sm">
                        Edit
                    </a>
                @endcan

                <a href="{{ route('products.index') }}" class="px-4 py-2 rounded bg-gray-100 hover:bg-gray-200 text-sm">
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
                <div class="bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        @if($product->image_path)
                            <img src="{{ asset('storage/'.$product->image_path) }}" class="w-full h-56 rounded object-cover border" alt="">
                        @else
                            <div class="w-full h-56 rounded bg-gray-100 border flex items-center justify-center text-gray-500">
                                No image
                            </div>
                        @endif
                    </div>
                </div>

                <div class="bg-white shadow-sm sm:rounded-lg lg:col-span-2">
                    <div class="p-6">
                        <h3 class="font-semibold text-gray-800 mb-2">BOM (per 1 product unit)</h3>

                        @if($product->materials->count() === 0)
                            <div class="p-3 rounded bg-gray-50 border text-gray-700">
                                No BOM configured yet. Click <b>Manage BOM</b>.
                            </div>
                        @else
                            <div class="overflow-x-auto">
                                <table class="min-w-full text-sm">
                                    <thead>
                                        <tr class="text-left text-gray-600 border-b">
                                            <th class="py-2">Material</th>
                                            <th class="py-2">Qty</th>
                                            <th class="py-2">Unit</th>
                                            <th class="py-2">Type</th>
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
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-4 text-sm text-gray-500">
                                Composite items are expanded into their children for stock evaluation in Phase 7.
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
