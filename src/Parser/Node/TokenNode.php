<?php

namespace RegexParser\Parser\Node;

use RegexParser\Lexer\TokenInterface;
use RegexParser\Parser\AbstractNode;

class TokenNode extends AbstractNode
{
    private TokenInterface $value;

    public function __construct(TokenInterface $token)
    {
        parent::__construct('token');

        $this->value = $token;
    }

    public function getValue(): TokenInterface
    {
        return $this->value;
    }
}
