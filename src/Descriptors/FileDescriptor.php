<?php

namespace Kelsoncm\Fwf\Descriptors;

class FileDescriptor
{
    public static array $hydratingArgs = ['details', 'header', 'footer', 'line_size'];

    protected ?HeaderRowDescriptor $header;
    /** @var array<DetailRowDescriptor> */
    protected array $details;
    protected ?FooterRowDescriptor $footer;
    protected int $lineSize;

    public function __construct(
        array $details,
        ?HeaderRowDescriptor $header = null,
        ?FooterRowDescriptor $footer = null,
        ?int $lineSize = null
    ) {
        if (empty($details)) {
            throw new \InvalidArgumentException("FileDescriptor must have at least one detail row descriptor.");
        }

        $this->header = $header;
        $this->details = array_values($details);
        $this->footer = $footer;

        $expectedLineSize = $details[0]->getLineSize();
        if ($lineSize !== null && $lineSize > 0) {
            $this->lineSize = $lineSize;
        } else {
            $this->lineSize = $expectedLineSize;
        }

        $this->validateLineSizes();
    }

    public function getHeader(): ?HeaderRowDescriptor
    {
        return $this->header;
    }

    /** @return array<DetailRowDescriptor> */
    public function getDetails(): array
    {
        return $this->details;
    }

    public function getFooter(): ?FooterRowDescriptor
    {
        return $this->footer;
    }

    public function getLineSize(): int
    {
        return $this->lineSize;
    }

    protected function validateLineSizes(): void
    {
        if ($this->header !== null && $this->header->getLineSize() !== $this->lineSize) {
            throw new \InvalidArgumentException("Header line size does not match file line size.");
        }
        foreach ($this->details as $detail) {
            if ($detail->getLineSize() !== $this->lineSize) {
                throw new \InvalidArgumentException("Detail line size does not match file line size.");
            }
        }
        if ($this->footer !== null && $this->footer->getLineSize() !== $this->lineSize) {
            throw new \InvalidArgumentException("Footer line size does not match file line size.");
        }
    }
}
