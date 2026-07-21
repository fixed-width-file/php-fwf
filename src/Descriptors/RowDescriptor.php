<?php

namespace Kelsoncm\Fwf\Descriptors;

use Kelsoncm\Fwf\Columns\AbstractColumn;

class RowDescriptor
{


    /** @var array<AbstractColumn> */
    protected array $columns;

    public function __construct(array $columns)
    {
        if (empty($columns)) {
            throw new \InvalidArgumentException("RowDescriptor must contain at least one column.");
        }
        $this->columns = array_values($columns);
        $this->validatePositions();
    }

    /** @return array<AbstractColumn> */
    public function getColumns(): array
    {
        return $this->columns;
    }

    public function getLineSize(): int
    {
        $sum = 0;
        foreach ($this->columns as $col) {
            $sum += $col->getSize();
        }
        return $sum;
    }

    public function validatePositions(): void
    {
        foreach ($this->columns as $col) {
            if (!$col instanceof AbstractColumn) {
                throw new \InvalidArgumentException("All elements must be instances of AbstractColumn.");
            }
        }
    }

    /**
     * Extracts values from a row line based on column definitions.
     * Handles multibyte UTF-8 characters safely.
     *
     * @return array<string, mixed>
     */
    public function getValues(string $rowLine): array
    {
        $result = [];
        $offset = 0;

        foreach ($this->columns as $col) {
            $slice = mb_substr($rowLine, $offset, $col->getSize(), 'UTF-8');
            $result[$col->getName()] = $col->toValue($slice);
            $offset += $col->getSize();
        }

        return $result;
    }
}
