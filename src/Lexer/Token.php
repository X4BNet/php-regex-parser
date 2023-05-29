<?php

namespace RegexParser\Lexer;

class Token implements TokenInterface
{
    protected string $name;

    /**
     * @var mixed
     */
    protected $value;

    /**
     * @param mixed  $value
     */
    public function __construct(string $name, $value = null)
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

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return sprintf('%s -> %s', $this->name, $this->value);
    }
}
