<?php

namespace Kelsoncm\Fwf\Tests;

use PHPUnit\Framework\TestCase;
use Kelsoncm\Fwf\Columns\CharColumn;
use Kelsoncm\Fwf\Columns\RightCharColumn;
use Kelsoncm\Fwf\Columns\PositiveIntegerColumn;
use Kelsoncm\Fwf\Columns\PositiveDecimalColumn;
use Kelsoncm\Fwf\Columns\DateColumn;
use Kelsoncm\Fwf\Columns\TimeColumn;
use Kelsoncm\Fwf\Columns\DateTimeColumn;

class ColumnsTest extends TestCase
{
    public function testCharColumn(): void
    {
        $col = new CharColumn("name", 10, "Name field");
        $this->assertEquals("name", $col->getName());
        $this->assertEquals(10, $col->getSize());
        $this->assertEquals("Name field", $col->getDescription());
        $this->assertEquals("FOO", $col->toValue("FOO       "));
        $this->assertTrue($col->validate("FOO       "));
    }

    public function testRightCharColumn(): void
    {
        $col = new RightCharColumn("code", 5);
        $this->assertEquals("BAR", $col->toValue("  BAR"));
    }

    public function testPositiveIntegerColumn(): void
    {
        $col = new PositiveIntegerColumn("age", 3);
        $this->assertEquals(45, $col->toValue("045"));
        $this->assertTrue($col->validate("045"));
        $this->assertFalse($col->validate("ABC"));

        $this->expectException(\InvalidArgumentException::class);
        $col->toValue("ABC");
    }

    public function testPositiveDecimalColumn(): void
    {
        $col = new PositiveDecimalColumn("price", 6, 2);
        $this->assertEquals(2, $col->getDecimals());
        $this->assertEquals(12.34, $col->toValue("001234"));
        $this->assertEquals(12.34, $col->toValue(" 12.34"));

        $this->assertFalse($col->validate("XYZ"));

        $colZeroDecimals = new PositiveDecimalColumn("qty", 5, 0);
        $this->assertEquals(100.0, $colZeroDecimals->toValue("00100"));
    }

    public function testPositiveDecimalColumnInvalid(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new PositiveDecimalColumn("invalid", 5, -1);
    }

    public function testPositiveDecimalColumnInvalidString(): void
    {
        $col = new PositiveDecimalColumn("price", 6, 2);
        $this->expectException(\InvalidArgumentException::class);
        $col->toValue("12.3A");
    }

    public function testDateColumn(): void
    {
        $col = new DateColumn("birthday", "Y-m-d");
        $this->assertEquals("Y-m-d", $col->getFormat());
        $dt = $col->toValue("2026-07-20");
        $this->assertEquals("2026-07-20", $dt->format("Y-m-d"));

        $this->assertNull($col->toValue("          "));
        $this->assertNull($col->toValue("0000000000"));
        $this->assertFalse($col->validate("invalid-date"));
    }

    public function testDateColumnInvalid(): void
    {
        $col = new DateColumn("birthday", "Y-m-d");
        $this->assertEquals("Y-m-d", $col->getFormat());
        $this->expectException(\InvalidArgumentException::class);
        $col->toValue("INVALID_DATE_STR");
    }

    public function testTimeColumn(): void
    {
        $col = new TimeColumn("start_time", "H:i:s");
        $this->assertEquals("H:i:s", $col->getFormat());
        $dt = $col->toValue("14:30:00");
        $this->assertEquals("14:30:00", $dt->format("H:i:s"));

        $this->assertNull($col->toValue("        "));
        $this->assertNull($col->toValue("00000000"));
    }

    public function testTimeColumnInvalid(): void
    {
        $col = new TimeColumn("start_time", "H:i:s");
        $this->assertEquals("H:i:s", $col->getFormat());
        $this->expectException(\InvalidArgumentException::class);
        $col->toValue("INVALID_TIME_STR");
    }

    public function testDateTimeColumn(): void
    {
        $col = new DateTimeColumn("created_at", "Y-m-d H:i:s");
        $this->assertEquals("Y-m-d H:i:s", $col->getFormat());
        $dt = $col->toValue("2026-07-20 14:30:00");
        $this->assertEquals("2026-07-20 14:30:00", $dt->format("Y-m-d H:i:s"));

        $this->assertNull($col->toValue("                   "));
        $this->assertNull($col->toValue("0000000000000000000"));
    }

    public function testDateTimeColumnInvalid(): void
    {
        $col = new DateTimeColumn("created_at", "Y-m-d H:i:s");
        $this->assertEquals("Y-m-d H:i:s", $col->getFormat());
        $this->expectException(\InvalidArgumentException::class);
        $col->toValue("INVALID_DATETIME_STR");
    }

    public function testInvalidSize(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new CharColumn("invalid", 0);
    }
}
