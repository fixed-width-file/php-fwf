<?php

namespace Kelsoncm\Fwf\Columns;

class CharColumn extends AbstractColumn
{
    public function toValue(string $slice): string
    {
        return rtrim($slice, ' ');
    }
}
