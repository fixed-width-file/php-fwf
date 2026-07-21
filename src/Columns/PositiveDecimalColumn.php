<?php

namespace Kelsoncm\Fwf\Columns;

class PositiveDecimalColumn extends AbstractColumn
{


    protected int $decimals;

    public function __construct(string $name, int $size, int $decimals = 2, ?string $description = null)
    {
        parent::__construct($name, $size, $description);
        if ($decimals < 0) {
            throw new \InvalidArgumentException("Decimals must be non-negative.");
        }
        $this->decimals = $decimals;
    }

    public function getDecimals(): int
    {
        return $this->decimals;
    }

    public function toValue(string $slice): float
    {
        $trimmed = trim($slice);
        if ($this->decimals > 0 && str_contains($trimmed, '.')) {
            if (!is_numeric($trimmed)) {
                throw new \InvalidArgumentException("Value '{$slice}' is not a valid positive decimal.");
            }
            return (float) $trimmed;
        }

        if (!ctype_digit($trimmed)) {
            throw new \InvalidArgumentException("Value '{$slice}' is not a valid positive decimal.");
        }

        $val = (int) $trimmed;
        return $this->decimals > 0 ? $val / (10 ** $this->decimals) : (float) $val;
    }
}
