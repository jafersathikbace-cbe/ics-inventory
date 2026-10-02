<?php

namespace Tests\Unit;

use App\Models\Material;
use App\Models\Product;
use App\Services\OrderStockService;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Collection;
use Tests\TestCase;

class OrderStockServiceTest extends TestCase
{
    public function test_it_calculates_raw_material_requirements(): void
    {
        $material = new Material([
            'name' => 'Steel Sheet',
            'is_composite' => false,
        ]);

        $material->id = 10;

        $pivot = new Pivot([
            'qty_per_product' => 2.5,
        ]);

        $material->setRelation('unit', null);
        $material->setRelation('components', collect());
        $material->setRelation('pivot', $pivot);

        $product = new Product();

        $product->setRelation(
            'materials',
            new Collection([$material])
        );

        $requirements = (new OrderStockService())->computeRequirements(
            $product,
            4
        );

        self::assertSame(
            10.0,
            $requirements[10]['required']
        );
    }

    public function test_it_expands_composite_materials_into_children(): void
    {
        $child = new Material([
            'name' => 'Fastener',
            'is_composite' => false,
        ]);

        $child->id = 20;
        $child->setRelation('unit', null);

        $component = new \App\Models\MaterialComponent([
            'qty_per_parent' => 3,
        ]);

        $component->setRelation(
            'childMaterial',
            $child
        );

        $composite = new Material([
            'name' => 'Assembly Kit',
            'is_composite' => true,
        ]);

        $composite->id = 30;

        $composite->setRelation(
            'components',
            collect([$component])
        );

        $composite->setRelation('unit', null);

        $composite->setRelation(
            'pivot',
            new Pivot([
                'qty_per_product' => 2,
            ])
        );

        $product = new Product();

        $product->setRelation(
            'materials',
            new Collection([$composite])
        );

        $requirements = (new OrderStockService())->computeRequirements(
            $product,
            5
        );

        self::assertSame(
            30.0,
            $requirements[20]['required']
        );
    }
}