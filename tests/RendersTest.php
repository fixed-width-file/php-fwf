<?php

namespace Kelsoncm\Fwf\Tests;

use PHPUnit\Framework\TestCase;
use Kelsoncm\Fwf\Columns\CharColumn;
use Kelsoncm\Fwf\Columns\PositiveIntegerColumn;
use Kelsoncm\Fwf\Descriptors\DetailRowDescriptor;
use Kelsoncm\Fwf\Descriptors\HeaderRowDescriptor;
use Kelsoncm\Fwf\Descriptors\FooterRowDescriptor;
use Kelsoncm\Fwf\Descriptors\FileDescriptor;
use Kelsoncm\Fwf\Renders\RenderUtils;

class RendersTest extends TestCase
{
    public function testRendersWithHeaderAndFooter(): void
    {
        $nameCol = new CharColumn("name", 10, "Name field");
        $ageCol = new PositiveIntegerColumn("age", 3, "Age field");

        $header = new HeaderRowDescriptor([$nameCol, $ageCol]);
        $detail = new DetailRowDescriptor([$nameCol, $ageCol]);
        $footer = new FooterRowDescriptor([$nameCol, $ageCol]);

        $fd = new FileDescriptor([$detail], $header, $footer);

        $md = RenderUtils::renderAsMarkdown($fd);
        $this->assertStringContainsString("File Descriptor Layout", $md);
        $this->assertStringContainsString("Header Row", $md);
        $this->assertStringContainsString("Detail Row 1", $md);
        $this->assertStringContainsString("Footer Row", $md);

        $rst = RenderUtils::renderAsRst($fd);
        $this->assertStringContainsString("File Descriptor Layout", $rst);
        $this->assertStringContainsString("Header Row", $rst);
        $this->assertStringContainsString("Detail Row 1", $rst);
        $this->assertStringContainsString("Footer Row", $rst);

        $html = RenderUtils::renderAsHtml($fd);
        $this->assertStringContainsString("<h1>File Descriptor Layout</h1>", $html);
        $this->assertStringContainsString("<h2>Header Row</h2>", $html);
        $this->assertStringContainsString("<h2>Detail Row 1</h2>", $html);
        $this->assertStringContainsString("<h2>Footer Row</h2>", $html);
        $this->assertStringContainsString("<table border=\"1\">", $html);
    }
}
