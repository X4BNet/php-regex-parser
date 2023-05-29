<?php

namespace RegexParser\Lexer;

class EscapeToken extends Token
{
    protected bool $isExclusionSequence;

    public function __construct(string $name, ?string $value, bool $isExclusionSequence = false)
    {
        parent::__construct($name, $value);

        $this->isExclusionSequence = $isExclusionSequence;
    }

    public function isExclusionSequence(): bool
    {
        return $this->isExclusionSequence;
    }
}
