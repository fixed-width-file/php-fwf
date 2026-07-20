<?php

namespace Kelsoncm\Fwf\Readers;

use Kelsoncm\Fwf\Descriptors\FileDescriptor;

class Reader implements \Iterator, \Countable
{
    protected array $lines = [];
    protected FileDescriptor $fileDescriptor;
    protected int $position = 0;
    protected string $newline;

    public function __construct(mixed $iterable, FileDescriptor $fileDescriptor, string $newline = "\n")
    {
        $this->fileDescriptor = $fileDescriptor;
        $this->newline = $newline;
        $this->lines = $this->extractLines($iterable);
        $this->validateAndNormalizeLines();
    }

    protected function extractLines(mixed $iterable): array
    {
        if (is_string($iterable)) {
            $normalized = str_replace("\r\n", "\n", $iterable);
            $normalized = str_replace("\r", "\n", $normalized);
            $rawLines = explode("\n", $normalized);
            if (end($rawLines) === '') {
                array_pop($rawLines);
            }
            return $rawLines;
        }

        if (is_resource($iterable)) {
            $lines = [];
            while (($line = fgets($iterable)) !== false) {
                $lines[] = rtrim($line, "\r\n");
            }
            return $lines;
        }

        if (is_array($iterable)) {
            return array_values($iterable);
        }

        throw new \InvalidArgumentException("Iterable source must be string, resource, or array.");
    }

    protected function validateAndNormalizeLines(): void
    {
        $expectedSize = $this->fileDescriptor->getLineSize();
        foreach ($this->lines as $idx => $line) {
            $lineLen = mb_strlen($line, 'UTF-8');
            if ($lineLen > $expectedSize) {
                $lineNum = $idx + 1;
                throw new \InvalidArgumentException(
                    "Line {$lineNum} size ({$lineLen}) exceeds expected size ({$expectedSize})."
                );
            }
            if ($lineLen < $expectedSize) {
                $this->lines[$idx] = $line . str_repeat(' ', $expectedSize - $lineLen);
            }
        }
    }

    public function count(): int
    {
        return count($this->lines);
    }

    public function current(): array
    {
        $line = $this->lines[$this->position];
        $totalLines = count($this->lines);

        // Header line
        if ($this->position === 0 && $this->fileDescriptor->getHeader() !== null) {
            return $this->fileDescriptor->getHeader()->getValues($line);
        }

        // Footer line
        if ($this->position === $totalLines - 1 && $this->fileDescriptor->getFooter() !== null) {
            return $this->fileDescriptor->getFooter()->getValues($line);
        }

        // Detail line
        $detailDescriptors = $this->fileDescriptor->getDetails();
        return $detailDescriptors[0]->getValues($line);
    }

    public function key(): int
    {
        return $this->position;
    }

    public function next(): void
    {
        $this->position++;
    }

    public function rewind(): void
    {
        $this->position = 0;
    }

    public function valid(): bool
    {
        return isset($this->lines[$this->position]);
    }
}
