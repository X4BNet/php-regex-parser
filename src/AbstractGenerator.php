<?php

namespace RegexParser;

use RegexParser\Parser\NodeInterface;

abstract class AbstractGenerator implements GeneratorInterface
{
    protected NodeInterface $ast;

    public function __construct(NodeInterface $ast)
    {
        $this->ast = $ast;
    }
}
