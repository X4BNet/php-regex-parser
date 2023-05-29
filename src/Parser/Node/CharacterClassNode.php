<?php

namespace RegexParser\Parser\Node;

use RegexParser\Parser\AbstractNode;

class CharacterClassNode extends AbstractNode
{
    public function __construct(TokenNode $start, TokenNode $end)
    {
        parent::__construct('character-class', [
            'start' => $start,
            'end' => $end,
        ]);
    }

    public function getStart(): TokenNode
    {
        return $this->value['start'];
    }

    public function getEnd(): TokenNode
    {
        return $this->value['end'];
    }
}
