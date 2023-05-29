<?php

namespace RegexParser;

/**
 * @template T
 * @implements StreamInterface<T>
 */
class Stream implements StreamInterface
{
    /**
     * @var list<T>
     */
    protected array $input;

    /**
     * @var int
     */
    protected int $cursor;

    /**
     * @param list<T> $input
     */
    public function __construct(array $input)
    {
        $this->input = $input;
        $this->cursor = -1;
    }

    /**
     * @return T|false
     */
    public function next()
    {
        if (!$this->hasNext()) {
            return false;
        }

        ++$this->cursor;

        return $this->current();
    }

    /**
     * @return T
     */
    public function current()
    {
        return $this->input[$this->cursor];
    }

    public function hasNext(): bool
    {
        return $this->cursor < count($this->input) - 1;
    }

    /**
     * @return T|false
     */
    public function readAt(int $index)
    {
        return $this->cursor + $index < count($this->input) ? $this->input[$this->cursor + $index] : false;
    }

    /**
     * @return int
     */
    public function cursor(): int
    {
        return $this->cursor;
    }

    /**
     * @return list<T>
     */
    public function input(): array
    {
        return $this->input;
    }

    /**
     * @param T $value
     */
    public function replace(int $index, $value): void
    {
        $this->input[$index] = $value;
    }

    /**
     * @return self<T>
     */
    public function clone(): self
    {
        return new self($this->input);
    }
}
