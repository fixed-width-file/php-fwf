<?php

namespace Kelsoncm\Fwf\Tests;

use PHPUnit\Framework\TestCase;
use Kelsoncm\Fwf\Columns\CharColumn;
use Kelsoncm\Fwf\Columns\PositiveIntegerColumn;
use Kelsoncm\Fwf\Descriptors\DetailRowDescriptor;
use Kelsoncm\Fwf\Descriptors\HeaderRowDescriptor;
use Kelsoncm\Fwf\Descriptors\FooterRowDescriptor;
use Kelsoncm\Fwf\Descriptors\FileDescriptor;

class DescriptorsTest extends TestCase
{
    public function testDescriptorsValidation(): void
    {
        $col1 = new CharColumn("name", 10);
        $col2 = new PositiveIntegerColumn("age", 3);

        $detail = new DetailRowDescriptor([$col1, $col2]);
        $this->assertEquals(13, $detail->getLineSize());
        $this->assertCount(2, $detail->getColumns());

        $fileDescriptor = new FileDescriptor([$detail]);
        $this->assertEquals(13, $fileDescriptor->getLineSize());
        $this->assertNull($fileDescriptor->getHeader());
        $this->assertNull($fileDescriptor->getFooter());
    }

    public function testMismatchedLineSize(): void
    {
        $col1 = new CharColumn("name", 10);
        $col2 = new CharColumn("id", 5);

        $header = new HeaderRowDescriptor([$col1]); // 10
        $detail = new DetailRowDescriptor([$col1, $col2]); // 15

        $this->expectException(\InvalidArgumentException::class);
        new FileDescriptor([$detail], $header);
    }
}
