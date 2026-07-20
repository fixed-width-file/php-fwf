<?php

namespace Kelsoncm\Fwf\Tests;

use PHPUnit\Framework\TestCase;
use Kelsoncm\Fwf\Columns\CharColumn;
use Kelsoncm\Fwf\Columns\PositiveIntegerColumn;
use Kelsoncm\Fwf\Descriptors\DetailRowDescriptor;
use Kelsoncm\Fwf\Descriptors\FileDescriptor;
use Kelsoncm\Fwf\Renders\RenderUtils;

class RendersTest extends TestCase
{
    public function testRenders(): void
    {
        $nameCol = new CharColumn("name", 10, "Name field");
        $ageCol = new PositiveIntegerColumn("age", 3, "Age field");
        $detail = new DetailRowDescriptor([$nameCol, $ageCol]);
        $fd = new FileDescriptor([$detail]);

        $md = RenderUtils::renderAsMarkdown($fd);
        $this->assertStringContainsString("File Descriptor Layout", $md);
        $this->assertStringContainsString("CharColumn", $md);

        $rst = RenderUtils::renderAsRst($fd);
        $this->assertStringContainsString("======================", $rst);
        $this->assertStringContainsString("PositiveIntegerColumn", $rst);

        $html = RenderUtils::renderAsHtml($fd);
        $this->assertStringContainsString("<h1>File Descriptor Layout</h1>", $html);
        $this->assertStringContainsString("<table border=\"1\">", $html);
    }
}
