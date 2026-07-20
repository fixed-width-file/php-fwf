<?php

namespace Kelsoncm\Fwf\Columns;

class RightCharColumn extends AbstractColumn
{
    public function toValue(string $slice): string
    {
        return trim($slice);
    }
}
