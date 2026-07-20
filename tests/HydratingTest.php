<?php

namespace Kelsoncm\Fwf\Tests;

use PHPUnit\Framework\TestCase;
use Kelsoncm\Fwf\Columns\CharColumn;
use Kelsoncm\Fwf\Columns\PositiveIntegerColumn;
use Kelsoncm\Fwf\Descriptors\DetailRowDescriptor;
use Kelsoncm\Fwf\Descriptors\FileDescriptor;
use Kelsoncm\Fwf\Hydrating\HydrateUtils;

class HydratingTest extends TestCase
{
    public function testHydrateAndDehydrate(): void
    {
        $repr = [
            'class_name' => 'CharColumn',
            'name' => 'username',
            'size' => 15,
            'description' => 'User login',
        ];

        $col = HydrateUtils::hydrateObject($repr);
        $this->assertInstanceOf(CharColumn::class, $col);
        $this->assertEquals('username', $col->getName());
        $this->assertEquals(15, $col->getSize());

        $dehydrated = HydrateUtils::dehydrateObject($col);
        $this->assertEquals(CharColumn::class, $dehydrated['class_name']);
        $this->assertEquals('username', $dehydrated['name']);
        $this->assertEquals(15, $dehydrated['size']);
    }

    public function testCrossLanguagePythonHydration(): void
    {
        $repr = [
            'class_name' => 'pyfwf.columns.PositiveIntegerColumn',
            'name' => 'count',
            'size' => 5,
        ];

        $col = HydrateUtils::hydrateObject($repr);
        $this->assertInstanceOf(PositiveIntegerColumn::class, $col);
        $this->assertEquals(5, $col->getSize());
    }

    public function testComplexDescriptorHydration(): void
    {
        $repr = [
            'class_name' => 'FileDescriptor',
            'details' => [
                [
                    'class_name' => 'DetailRowDescriptor',
                    'columns' => [
                        [
                            'class_name' => 'CharColumn',
                            'name' => 'name',
                            'size' => 10,
                        ],
                    ],
                ],
            ],
        ];

        $fd = HydrateUtils::hydrateObject($repr);
        $this->assertInstanceOf(FileDescriptor::class, $fd);
        $this->assertEquals(10, $fd->getLineSize());
    }
}
