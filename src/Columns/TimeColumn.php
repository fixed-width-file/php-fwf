<?php

namespace Kelsoncm\Fwf\Columns;

class TimeColumn extends AbstractColumn
{
    public static array $hydratingArgs = ['name', 'format', 'description'];

    protected string $format;

    public function __construct(string $name, string $format = 'H:i:s', ?string $description = null)
    {
        $phpFormat = str_replace(['%H', '%M', '%S', '%i', '%s'], ['H', 'i', 's', 'i', 's'], $format);
        $size = strlen(date($phpFormat));
        if ($size === 0) {
            $size = 8;
        }
        parent::__construct($name, $size, $description);
        $this->format = $phpFormat;
    }

    public function getFormat(): string
    {
        return $this->format;
    }

    public function toValue(string $slice): ?\DateTimeImmutable
    {
        $trimmed = trim($slice);
        if ($trimmed === '' || preg_match('/^0+$/', $trimmed)) {
            return null;
        }

        $dt = \DateTimeImmutable::createFromFormat('!' . $this->format, $trimmed);
        if ($dt === false) {
            $dt = \DateTimeImmutable::createFromFormat($this->format, $trimmed);
        }
        if ($dt === false) {
            throw new \InvalidArgumentException("Value '{$slice}' does not match time format '{$this->format}'.");
        }
        return $dt;
    }
}
