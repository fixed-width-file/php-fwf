<?php

namespace Kelsoncm\Fwf\Columns;

abstract class AbstractColumn
{


    protected string $name;
    protected int $size;
    protected ?string $description;

    public function __construct(string $name, int $size, ?string $description = null)
    {
        if ($size <= 0) {
            throw new \InvalidArgumentException("Column size must be positive.");
        }
        $this->name = $name;
        $this->size = $size;
        $this->description = $description;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSize(): int
    {
        return $this->size;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    abstract public function toValue(string $slice): mixed;

    public function validate(string $slice): bool
    {
        try {
            $this->toValue($slice);
            return true;
        } catch (\Throwable) {
            return false;
        }
    }
}
