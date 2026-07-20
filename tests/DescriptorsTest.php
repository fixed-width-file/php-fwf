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

        $header = new HeaderRowDescriptor([$col1, $col2]);
        $detail = new DetailRowDescriptor([$col1, $col2]);
        $footer = new FooterRowDescriptor([$col1, $col2]);

        $this->assertEquals(13, $detail->getLineSize());
        $this->assertCount(2, $detail->getColumns());

        $fileDescriptor = new FileDescriptor([$detail], $header, $footer);
        $this->assertEquals(13, $fileDescriptor->getLineSize());
        $this->assertSame($header, $fileDescriptor->getHeader());
        $this->assertSame($footer, $fileDescriptor->getFooter());
        $this->assertCount(1, $fileDescriptor->getDetails());
    }

    public function testEmptyRowDescriptor(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new DetailRowDescriptor([]);
    }

    public function testInvalidColumnTypeInRowDescriptor(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new DetailRowDescriptor(["not_a_column"]);
    }

    public function testEmptyFileDescriptor(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new FileDescriptor([]);
    }

    public function testMismatchedHeaderLineSize(): void
    {
        $col1 = new CharColumn("name", 10);
        $col2 = new CharColumn("id", 5);

        $header = new HeaderRowDescriptor([$col1]); // 10
        $detail = new DetailRowDescriptor([$col1, $col2]); // 15

        $this->expectException(\InvalidArgumentException::class);
        new FileDescriptor([$detail], $header);
    }

    public function testMismatchedDetailLineSize(): void
    {
        $col1 = new CharColumn("name", 10);
        $col2 = new CharColumn("id", 5);

        $detail1 = new DetailRowDescriptor([$col1, $col2]); // 15
        $detail2 = new DetailRowDescriptor([$col1]); // 10

        $this->expectException(\InvalidArgumentException::class);
        new FileDescriptor([$detail1, $detail2]);
    }

    public function testMismatchedFooterLineSize(): void
    {
        $col1 = new CharColumn("name", 10);
        $col2 = new CharColumn("id", 5);

        $detail = new DetailRowDescriptor([$col1, $col2]); // 15
        $footer = new FooterRowDescriptor([$col1]); // 10

        $this->expectException(\InvalidArgumentException::class);
        new FileDescriptor([$detail], null, $footer);
    }
}
