<?php

namespace RegexParser\Lexer;

class EscapeToken extends Token
{
    /**
     * @var bool
     */
    protected bool $isExclusionSequence;

    /**
     * @param mixed $value
     */
    public function __construct(string $name, $value, bool $isExclusionSequence = false)
    {
        parent::__construct($name, $value);

        $this->isExclusionSequence = $isExclusionSequence;
    }

    public function setIsExclusionSequence(bool $isExclusionSequence): void
    {
        $this->isExclusionSequence = $isExclusionSequence;
    }

    public function isExclusionSequence(): bool
    {
        return $this->isExclusionSequence;
    }
}
