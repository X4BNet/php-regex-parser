<?php

namespace RegexParser\Parser\Node;

use RegexParser\Parser\AbstractNode;

class CharacterClassNode extends AbstractNode
{
    private TokenNode $start;
    private TokenNode $end;

    public function __construct(TokenNode $start, TokenNode $end)
    {
        parent::__construct('character-class');

        $this->start = $start;
        $this->end = $end;
    }

    public function getStart(): TokenNode
    {
        return $this->start;
    }

    public function getEnd(): TokenNode
    {
        return $this->end;
    }
}
