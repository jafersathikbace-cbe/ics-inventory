@php
    $indent = $level * 18;
@endphp

<div style="margin-left: {{ $indent }}px;" class="mb-2">
    <div class="flex items-center gap-2">
        <span class="font-semibold text-gray-800">{{ $material->name }}</span>
        <span class="text-xs text-gray-500">({{ $material->unit?->symbol }})</span>

        @if($material->is_composite)
            <span class="text-xs px-2 py-1 rounded bg-yellow-100 text-yellow-800">Composite</span>
        @else
            <span class="text-xs px-2 py-1 rounded bg-gray-100 text-gray-700">Raw</span>
        @endif
    </div>

    @if($material->is_composite && $material->components && $material->components->count() > 0)
        <div class="mt-2 space-y-2">
            @foreach($material->components as $comp)
                <div style="margin-left: 18px;" class="p-2 rounded bg-white border">
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="font-medium text-gray-800">{{ $comp->childMaterial?->name }}</span>
                            <span class="text-xs text-gray-500">({{ $comp->childMaterial?->unit?->symbol }})</span>
                        </div>
                        <div class="text-sm text-gray-700">
                            <span class="font-semibold">{{ $comp->qty_per_parent }}</span>
                            <span class="text-xs text-gray-500">per 1 {{ $material->unit?->symbol }}</span>
                        </div>
                    </div>

                    @if($comp->childMaterial?->is_composite)
                        <div class="mt-2">
                            @include('materials.partials.composite_tree', [
                                'material' => $comp->childMaterial,
                                'level' => $level + 1
                            ])
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
</div>
