<?php

namespace RegexParser\Lexer;

interface TokenInterface
{
    public function is(string $tokenName): bool;

    public function getName(): string;

    public function getValue(): ?string;
}
