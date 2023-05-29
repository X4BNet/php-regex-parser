<?php

namespace RegexParser\Lexer;

class Token implements TokenInterface
{
    protected string $name;

    /**
     * @var ?string
     */
    protected $value;

    /**
     * @param ?string $value
     */
    public function __construct(string $name, ?string $value = null)
    {
        $this->name = $name;
        $this->value = $value;
    }

    public function is(string $tokenName): bool
    {
        return $this->name === $tokenName;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return sprintf('%s -> %s', $this->name, $this->value);
    }
}
