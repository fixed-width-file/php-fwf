<?php

namespace Kelsoncm\Fwf\Renders;

use Kelsoncm\Fwf\Descriptors\FileDescriptor;
use Kelsoncm\Fwf\Descriptors\RowDescriptor;

class RenderUtils
{
    public static function renderAsMarkdown(FileDescriptor $fileDescriptor): string
    {
        $out = "# File Descriptor Layout\n\n";

        if ($fileDescriptor->getHeader() !== null) {
            $out .= "## Header Row\n\n";
            $out .= self::renderRowDescriptorAsMarkdown($fileDescriptor->getHeader());
            $out .= "\n";
        }

        $details = $fileDescriptor->getDetails();
        foreach ($details as $idx => $detail) {
            $num = $idx + 1;
            $out .= "## Detail Row {$num}\n\n";
            $out .= self::renderRowDescriptorAsMarkdown($detail);
            $out .= "\n";
        }

        if ($fileDescriptor->getFooter() !== null) {
            $out .= "## Footer Row\n\n";
            $out .= self::renderRowDescriptorAsMarkdown($fileDescriptor->getFooter());
            $out .= "\n";
        }

        return $out;
    }

    protected static function renderRowDescriptorAsMarkdown(RowDescriptor $descriptor): string
    {
        $out = "| Name | Size | Type | Description |\n";
        $out .= "| --- | --- | --- | --- |\n";

        foreach ($descriptor->getColumns() as $col) {
            $shortType = (new \ReflectionClass($col))->getShortName();
            $desc = $col->getDescription() ?? '';
            $out .= "| {$col->getName()} | {$col->getSize()} | {$shortType} | {$desc} |\n";
        }

        return $out;
    }

    public static function renderAsRst(FileDescriptor $fileDescriptor): string
    {
        $out = "File Descriptor Layout\n";
        $out .= "======================\n\n";

        if ($fileDescriptor->getHeader() !== null) {
            $out .= "Header Row\n";
            $out .= "----------\n\n";
            $out .= self::renderRowDescriptorAsRst($fileDescriptor->getHeader());
            $out .= "\n";
        }

        $details = $fileDescriptor->getDetails();
        foreach ($details as $idx => $detail) {
            $num = $idx + 1;
            $out .= "Detail Row {$num}\n";
            $out .= "------------\n\n";
            $out .= self::renderRowDescriptorAsRst($detail);
            $out .= "\n";
        }

        if ($fileDescriptor->getFooter() !== null) {
            $out .= "Footer Row\n";
            $out .= "----------\n\n";
            $out .= self::renderRowDescriptorAsRst($fileDescriptor->getFooter());
            $out .= "\n";
        }

        return $out;
    }

    protected static function renderRowDescriptorAsRst(RowDescriptor $descriptor): string
    {
        $rows = [];
        $headers = ['Name', 'Size', 'Type', 'Description'];
        $wName = strlen($headers[0]);
        $wSize = strlen($headers[1]);
        $wType = strlen($headers[2]);
        $wDesc = strlen($headers[3]);

        foreach ($descriptor->getColumns() as $col) {
            $typeName = (new \ReflectionClass($col))->getShortName();
            $desc = $col->getDescription() ?? '';
            $wName = max($wName, strlen($col->getName()));
            $wSize = max($wSize, strlen((string) $col->getSize()));
            $wType = max($wType, strlen($typeName));
            $wDesc = max($wDesc, strlen($desc));

            $rows[] = [
                'name' => $col->getName(),
                'size' => (string) $col->getSize(),
                'type' => $typeName,
                'desc' => $desc,
            ];
        }

        $sep = '+' . str_repeat('-', $wName + 2) . '+' . str_repeat('-', $wSize + 2) . '+' . str_repeat('-', $wType + 2) . '+' . str_repeat('-', $wDesc + 2) . "+\n";
        $headSep = '+' . str_repeat('=', $wName + 2) . '+' . str_repeat('=', $wSize + 2) . '+' . str_repeat('=', $wType + 2) . '+' . str_repeat('=', $wDesc + 2) . "+\n";

        $out = $sep;
        $out .= sprintf("| %-{$wName}s | %-{$wSize}s | %-{$wType}s | %-{$wDesc}s |\n", ...$headers);
        $out .= $headSep;

        foreach ($rows as $r) {
            $out .= sprintf("| %-{$wName}s | %-{$wSize}s | %-{$wType}s | %-{$wDesc}s |\n", $r['name'], $r['size'], $r['type'], $r['desc']);
            $out .= $sep;
        }

        return $out;
    }

    public static function renderAsHtml(FileDescriptor $fileDescriptor): string
    {
        $out = "<h1>File Descriptor Layout</h1>\n";

        if ($fileDescriptor->getHeader() !== null) {
            $out .= "<h2>Header Row</h2>\n";
            $out .= self::renderRowDescriptorAsHtml($fileDescriptor->getHeader());
        }

        $details = $fileDescriptor->getDetails();
        foreach ($details as $idx => $detail) {
            $num = $idx + 1;
            $out .= "<h2>Detail Row {$num}</h2>\n";
            $out .= self::renderRowDescriptorAsHtml($detail);
        }

        if ($fileDescriptor->getFooter() !== null) {
            $out .= "<h2>Footer Row</h2>\n";
            $out .= self::renderRowDescriptorAsHtml($fileDescriptor->getFooter());
        }

        return $out;
    }

    protected static function renderRowDescriptorAsHtml(RowDescriptor $descriptor): string
    {
        $out = "<table border=\"1\">\n";
        $out .= "  <thead>\n";
        $out .= "    <tr><th>Name</th><th>Size</th><th>Type</th><th>Description</th></tr>\n";
        $out .= "  </thead>\n";
        $out .= "  <tbody>\n";

        foreach ($descriptor->getColumns() as $col) {
            $shortType = (new \ReflectionClass($col))->getShortName();
            $desc = htmlspecialchars($col->getDescription() ?? '');
            $name = htmlspecialchars($col->getName());
            $out .= "    <tr><td>{$name}</td><td>{$col->getSize()}</td><td>{$shortType}</td><td>{$desc}</td></tr>\n";
        }

        $out .= "  </tbody>\n";
        $out .= "</table>\n";
        return $out;
    }
}
