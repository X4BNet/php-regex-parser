<?php

namespace RegexParser\Lexer;

interface TokenInterface
{
    public function is(string $tokenName): bool;

    public function getName(): string;

    /**
     * @return mixed
     */
    public function getValue();
}
