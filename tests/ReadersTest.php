<?php

namespace Kelsoncm\Fwf\Tests;

use PHPUnit\Framework\TestCase;
use Kelsoncm\Fwf\Columns\CharColumn;
use Kelsoncm\Fwf\Columns\PositiveIntegerColumn;
use Kelsoncm\Fwf\Descriptors\DetailRowDescriptor;
use Kelsoncm\Fwf\Descriptors\FileDescriptor;
use Kelsoncm\Fwf\Readers\Reader;

class ReadersTest extends TestCase
{
    public function testReaderStringContent(): void
    {
        $nameCol = new CharColumn("name", 10);
        $ageCol = new PositiveIntegerColumn("age", 3);
        $detail = new DetailRowDescriptor([$nameCol, $ageCol]);
        $fd = new FileDescriptor([$detail]);

        $content = "KELSON    045\nMARIA     030\n";
        $reader = new Reader($content, $fd, "\n");

        $this->assertCount(2, $reader);
        $rows = iterator_to_array($reader);

        $this->assertEquals("KELSON", $rows[0]["name"]);
        $this->assertEquals(45, $rows[0]["age"]);
        $this->assertEquals("MARIA", $rows[1]["name"]);
        $this->assertEquals(30, $rows[1]["age"]);
    }

    public function testReaderExceedingLineLength(): void
    {
        $nameCol = new CharColumn("name", 10);
        $detail = new DetailRowDescriptor([$nameCol]);
        $fd = new FileDescriptor([$detail]);

        $content = "VERY_VERY_LONG_LINE_EXCEEDING_EXPECTED_SIZE\n";

        $this->expectException(\InvalidArgumentException::class);
        new Reader($content, $fd);
    }
}
