<?php

namespace RegexParser\Parser\Node;

use RegexParser\Parser\AbstractNode;
use RegexParser\Parser\NodeInterface;

class RepetitionNode extends AbstractNode
{
    /**
     * @param list<NodeInterface> $childNodes
     */
    public function __construct(?int $min, ?int $max, array $childNodes)
    {
        parent::__construct('repetition', [
            'min' => $min,
            'max' => $max,
        ], $childNodes);
    }

    public function getMin(): ?int
    {
        return $this->value['min'];
    }

    public function getMax(): ?int
    {
        return $this->value['max'];
    }
}
