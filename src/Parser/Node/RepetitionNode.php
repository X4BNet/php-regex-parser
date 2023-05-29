<?php

namespace RegexParser\Parser\Node;

use RegexParser\Parser\AbstractNode;
use RegexParser\Parser\NodeInterface;

class RepetitionNode extends AbstractNode
{
    private ?int $min;
    private ?int $max;

    /**
     * @param list<NodeInterface> $childNodes
     */
    public function __construct(?int $min, ?int $max, array $childNodes)
    {
        parent::__construct('repetition', $childNodes);

        $this->min = $min;
        $this->max = $max;
    }

    public function getMin(): ?int
    {
        return $this->min;
    }

    public function getMax(): ?int
    {
        return $this->max;
    }
}
