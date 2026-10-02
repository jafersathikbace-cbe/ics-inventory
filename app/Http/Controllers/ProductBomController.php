<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductBomController extends Controller
{
    public function index(Product $product)
    {
        $product->load(['materials.unit', 'materials.components.childMaterial.unit']);

        $allMaterials = Material::with('unit')
            ->orderBy('name')
            ->get();

        // Expanded requirements preview (flatten composite children into raw list)
        // Output format: material_id => ['name'=>..., 'unit'=>..., 'qty'=>...]
        $expanded = $this->computeExpandedRequirements($product);

        return view('products.bom.index', [
            'product' => $product,
            'allMaterials' => $allMaterials,
            'expanded' => $expanded,
        ]);
    }

    public function store(Request $request, Product $product)
    {
        $data = $request->validate([
            'material_id' => ['required', 'integer', 'exists:materials,id'],
            'qty_per_product' => ['required', 'numeric', 'gt:0'],
        ]);

        DB::transaction(function () use ($product, $data) {
            // Upsert pivot row
            $product->materials()->syncWithoutDetaching([
                (int)$data['material_id'] => ['qty_per_product' => $data['qty_per_product']],
            ]);
        });

        return redirect()->route('products.bom.index', $product)->with('success', 'BOM material saved successfully.');
    }

    public function destroy(Product $product, $materialId)
    {
        $product->materials()->detach((int)$materialId);

        return redirect()->route('products.bom.index', $product)->with('success', 'BOM material removed successfully.');
    }

    /**
     * Expand BOM for composite materials into raw materials.
     * - If material is raw: add qty_per_product
     * - If composite: add each child qty = qty_per_product * qty_per_parent
     */
    private function computeExpandedRequirements(Product $product): array
    {
        $expanded = [];

        foreach ($product->materials as $m) {
            $qtyPerProduct = (float) $m->pivot->qty_per_product;

            if (!$m->is_composite) {
                $expanded[$m->id] = $this->accumulate($expanded, $m->id, $m->name, $m->unit?->symbol, $qtyPerProduct);
                continue;
            }

            // Composite: expand one level (we currently disallow composite children, so one level is enough)
            foreach ($m->components as $comp) {
                $child = $comp->childMaterial;
                if (!$child) continue;

                $childQty = $qtyPerProduct * (float)$comp->qty_per_parent;

                $expanded[$child->id] = $this->accumulate(
                    $expanded,
                    $child->id,
                    $child->name,
                    $child->unit?->symbol,
                    $childQty
                );
            }
        }

        // sort by name for display
        uasort($expanded, fn($a, $b) => strcmp($a['name'], $b['name']));

        return $expanded;
    }

    private function accumulate(array $expanded, int $id, string $name, ?string $unitSymbol, float $qty): array
    {
        if (!isset($expanded[$id])) {
            return [
                'id' => $id,
                'name' => $name,
                'unit' => $unitSymbol ?? '',
                'qty' => round($qty, 3),
            ];
        }

        $expanded[$id]['qty'] = round(((float)$expanded[$id]['qty']) + $qty, 3);
        return $expanded[$id];
    }
}
