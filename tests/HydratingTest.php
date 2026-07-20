<?php

namespace Kelsoncm\Fwf\Tests;

use PHPUnit\Framework\TestCase;
use Kelsoncm\Fwf\Columns\CharColumn;
use Kelsoncm\Fwf\Columns\PositiveIntegerColumn;
use Kelsoncm\Fwf\Descriptors\DetailRowDescriptor;
use Kelsoncm\Fwf\Descriptors\HeaderRowDescriptor;
use Kelsoncm\Fwf\Descriptors\FooterRowDescriptor;
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

    public function testHydrateWithTypeAndRowTypes(): void
    {
        $colRepr = [
            'type' => 'char',
            'name' => 'field1',
            'size' => 10,
        ];
        $col = HydrateUtils::hydrateObject($colRepr);
        $this->assertInstanceOf(CharColumn::class, $col);

        $headerRepr = [
            'row_type' => 'header',
            'columns' => [$colRepr],
        ];
        $header = HydrateUtils::hydrateObject($headerRepr);
        $this->assertInstanceOf(HeaderRowDescriptor::class, $header);

        $footerRepr = [
            'row_type' => 'footer',
            'columns' => [$colRepr],
        ];
        $footer = HydrateUtils::hydrateObject($footerRepr);
        $this->assertInstanceOf(FooterRowDescriptor::class, $footer);
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

    public function testComplexDescriptorHydrationAndDehydration(): void
    {
        $col1 = new CharColumn("name", 10);
        $col2 = new PositiveIntegerColumn("age", 3);

        $header = new HeaderRowDescriptor([$col1, $col2]);
        $detail = new DetailRowDescriptor([$col1, $col2]);
        $footer = new FooterRowDescriptor([$col1, $col2]);
        $fd = new FileDescriptor([$detail], $header, $footer);

        $dehydrated = HydrateUtils::dehydrateObject($fd);
        $this->assertIsArray($dehydrated);

        $hydratedFd = HydrateUtils::hydrateObject($dehydrated);
        $this->assertInstanceOf(FileDescriptor::class, $hydratedFd);
        $this->assertEquals(13, $hydratedFd->getLineSize());
    }

    public function testInvalidClassNameResolution(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        HydrateUtils::resolveClassName("NonExistentClass123");
    }

    public function testMissingKeysInMap(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        HydrateUtils::resolveClassName([]);
    }
}
