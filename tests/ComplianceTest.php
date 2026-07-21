<?php

namespace Kelsoncm\Fwf\Tests;

use PHPUnit\Framework\TestCase;
use Kelsoncm\Fwf\Columns\AbstractColumn;
use Kelsoncm\Fwf\Columns\CharColumn;
use Kelsoncm\Fwf\Columns\RightCharColumn;
use Kelsoncm\Fwf\Columns\PositiveIntegerColumn;
use Kelsoncm\Fwf\Columns\PositiveDecimalColumn;
use Kelsoncm\Fwf\Columns\DateColumn;
use Kelsoncm\Fwf\Columns\TimeColumn;
use Kelsoncm\Fwf\Columns\DateTimeColumn;
use Kelsoncm\Fwf\Descriptors\FileDescriptor;
use Kelsoncm\Fwf\Descriptors\DetailRowDescriptor;
use Kelsoncm\Fwf\Descriptors\HeaderRowDescriptor;
use Kelsoncm\Fwf\Descriptors\FooterRowDescriptor;
use Kelsoncm\Fwf\Readers\Reader;

class ComplianceTest extends TestCase
{
    protected string $complianceDir;

    protected function setUp(): void
    {
        $localPath = __DIR__ . '/../fwf-compliance-tests';
        $parentPath = __DIR__ . '/../../fwf-compliance-tests';

        if (file_exists($localPath . '/manifest.json')) {
            $this->complianceDir = realpath($localPath);
        } elseif (file_exists($parentPath . '/manifest.json')) {
            $this->complianceDir = realpath($parentPath);
        } else {
            $this->markTestSkipped("fwf-compliance-tests repository not found.");
        }
    }

    public function testComplianceSuite(): void
    {
        $manifestJson = file_get_contents($this->complianceDir . '/manifest.json');
        $manifest = json_decode($manifestJson, true);

        $this->assertNotEmpty($manifest['cases'], "Compliance manifest must contain test cases.");

        foreach ($manifest['cases'] as $caseRef) {
            $caseId = $caseRef['id'];
            $casePath = $this->complianceDir . '/cases/' . $caseId;

            $descriptorJson = file_get_contents($casePath . '/descriptor.json');
            $descriptorMap = json_decode($descriptorJson, true);

            $fileDescriptor = $this->buildFileDescriptor($descriptorMap);
            $this->assertInstanceOf(FileDescriptor::class, $fileDescriptor);

            $inputContent = file_get_contents($casePath . '/input.fwf');
            $expectedJson = file_get_contents($casePath . '/expected.json');
            $expectedData = json_decode($expectedJson, true);

            $reader = new Reader($inputContent, $fileDescriptor, "\n");
            $parsedRows = [];
            foreach ($reader as $row) {
                $parsedRows[] = $row;
            }

            $this->assertCount(
                count($expectedData),
                $parsedRows,
                "Case '{$caseId}' row count mismatch."
            );

            foreach ($expectedData as $idx => $expectedRow) {
                foreach ($expectedRow as $colName => $expectedVal) {
                    $actualVal = $parsedRows[$idx][$colName] ?? null;

                    if ($actualVal instanceof \DateTimeInterface) {
                        $expStr = (string) $expectedVal;
                        if (strlen($expStr) === 10) {
                            $actualVal = $actualVal->format('Y-m-d');
                        } elseif (str_contains($expStr, 'T')) {
                            $actualVal = $actualVal->format('Y-m-d\TH:i:s');
                        } elseif (strlen($expStr) === 8 && str_contains($expStr, ':')) {
                            $actualVal = $actualVal->format('H:i:s');
                        } else {
                            $actualVal = $actualVal->format('Y-m-d H:i:s');
                        }
                    }

                    if (is_float($expectedVal)) {
                        $this->assertEqualsWithDelta(
                            $expectedVal,
                            $actualVal,
                            0.0001,
                            "Case '{$caseId}' row {$idx} col '{$colName}' mismatch."
                        );
                    } else {
                        $this->assertEquals(
                            $expectedVal,
                            $actualVal,
                            "Case '{$caseId}' row {$idx} col '{$colName}' mismatch."
                        );
                    }
                }
            }
        }
    }

    private function buildColumn(array $colDef): AbstractColumn
    {
        $cType = $colDef['type'] ?? 'char';
        $name = $colDef['name'] ?? '';
        $desc = $colDef['description'] ?? $name;
        $size = $colDef['size'] ?? 0;

        return match ($cType) {
            'char' => new CharColumn($name, $size, $desc),
            'right_char' => new RightCharColumn($name, $size, $desc),
            'positive_integer' => new PositiveIntegerColumn($name, $size, $desc),
            'positive_decimal' => new PositiveDecimalColumn($name, $size, $colDef['decimals'] ?? 2, $desc),
            'date' => new DateColumn($name, $colDef['format'] ?? '%d%m%Y', $desc),
            'time' => new TimeColumn($name, $colDef['format'] ?? '%H%M', $desc),
            'datetime' => new DateTimeColumn($name, $colDef['format'] ?? '%d%m%Y%H%M', $desc),
            default => throw new \InvalidArgumentException("Unknown column type: {$cType}"),
        };
    }

    private function buildFileDescriptor(array $data): FileDescriptor
    {
        $header = null;
        if (!empty($data['header'])) {
            $cols = array_map(fn($c) => $this->buildColumn($c), $data['header']['columns']);
            $header = new HeaderRowDescriptor($cols);
        }

        $footer = null;
        if (!empty($data['footer'])) {
            $cols = array_map(fn($c) => $this->buildColumn($c), $data['footer']['columns']);
            $footer = new FooterRowDescriptor($cols);
        }

        $details = [];
        foreach ($data['details'] as $d) {
            $cols = array_map(fn($c) => $this->buildColumn($c), $d['columns']);
            $details[] = new DetailRowDescriptor($cols);
        }

        return new FileDescriptor($details, $header, $footer);
    }
}
