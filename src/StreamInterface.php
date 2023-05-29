<?php

namespace RegexParser;

/**
 * @template-covariant T
 */
interface StreamInterface
{
    /**
     * @return T|false
     */
    public function next();

    /**
     * @return T|false
     */
    public function readAt(int $index);

    /**
     * @return T
     */
    public function current();

    /**
     * @return list<T>
     */
    public function input(): array;

    public function hasNext(): bool;

    public function cursor(): int;

    /**
     * @param mixed $value
     */
    public function replace(int $index, $value): void;

    /**
     * @return StreamInterface<T>
     */
    public function clone(): StreamInterface;
}
