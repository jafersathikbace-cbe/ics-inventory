<?php

namespace Tests\Unit;

use App\Models\Material;
use PHPUnit\Framework\TestCase;

class MaterialTest extends TestCase
{
    public function test_material_is_low_when_stock_reaches_threshold(): void
    {
        $material = new Material([
            'stock' => 5,
            'threshold' => 5,
        ]);

        self::assertTrue($material->is_low);
    }

    public function test_material_is_not_low_above_threshold(): void
    {
        $material = new Material([
            'stock' => 5.001,
            'threshold' => 5,
        ]);

        self::assertFalse($material->is_low);
    }
}
