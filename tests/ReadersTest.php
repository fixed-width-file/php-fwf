<?php

namespace Kelsoncm\Fwf\Tests;

use PHPUnit\Framework\TestCase;
use Kelsoncm\Fwf\Columns\CharColumn;
use Kelsoncm\Fwf\Columns\PositiveIntegerColumn;
use Kelsoncm\Fwf\Descriptors\DetailRowDescriptor;
use Kelsoncm\Fwf\Descriptors\HeaderRowDescriptor;
use Kelsoncm\Fwf\Descriptors\FooterRowDescriptor;
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

    public function testReaderArrayAndStreamInputWithHeaderAndFooter(): void
    {
        $col1 = new CharColumn("type", 1);
        $col2 = new CharColumn("data", 12);

        $header = new HeaderRowDescriptor([$col1, $col2]);
        $detail = new DetailRowDescriptor([$col1, $col2]);
        $footer = new FooterRowDescriptor([$col1, $col2]);
        $fd = new FileDescriptor([$detail], $header, $footer);

        $lines = [
            "HHEADER_DATA_",
            "DDETAIL_DATA_",
            "FFOOTER_DATA_",
        ];

        $readerArray = new Reader($lines, $fd);
        $this->assertCount(3, $readerArray);

        // Test Iterator methods
        $readerArray->rewind();
        $this->assertTrue($readerArray->valid());
        $this->assertEquals(0, $readerArray->key());

        $headerRow = $readerArray->current();
        $this->assertEquals("H", $headerRow["type"]);

        $readerArray->next();
        $this->assertEquals(1, $readerArray->key());
        $detailRow = $readerArray->current();
        $this->assertEquals("D", $detailRow["type"]);

        $readerArray->next();
        $footerRow = $readerArray->current();
        $this->assertEquals("F", $footerRow["type"]);

        $readerArray->next();
        $this->assertFalse($readerArray->valid());

        // Stream test
        $stream = fopen('php://memory', 'r+');
        fwrite($stream, implode("\n", $lines) . "\n");
        rewind($stream);

        $readerStream = new Reader($stream, $fd);
        $this->assertCount(3, $readerStream);
        fclose($stream);
    }

    public function testReaderInvalidIterableType(): void
    {
        $col = new CharColumn("name", 10);
        $detail = new DetailRowDescriptor([$col]);
        $fd = new FileDescriptor([$detail]);

        $this->expectException(\InvalidArgumentException::class);
        new Reader(12345, $fd);
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
