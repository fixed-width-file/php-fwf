<?php

namespace Kelsoncm\Fwf\Columns;

class PositiveIntegerColumn extends AbstractColumn
{
    public function toValue(string $slice): int
    {
        $trimmed = trim($slice);
        if (!ctype_digit($trimmed)) {
            throw new \InvalidArgumentException("Value '{$slice}' is not a valid positive integer.");
        }
        return (int) $trimmed;
    }
}
