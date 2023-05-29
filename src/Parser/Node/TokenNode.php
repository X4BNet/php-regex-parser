<?php

namespace RegexParser\Parser\Node;

use RegexParser\Lexer\TokenInterface;
use RegexParser\Parser\AbstractNode;

class TokenNode extends AbstractNode
{
    public function __construct(TokenInterface $token)
    {
        parent::__construct('token', $token);
    }
}
