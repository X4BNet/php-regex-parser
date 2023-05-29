<?php

namespace RegexParser\Parser\Node;

use RegexParser\Parser\AbstractNode;
use RegexParser\Parser\NodeInterface;

class AlternativeNode extends AbstractNode
{
    /**
     * @param list<NodeInterface> $childNodes
     */
    public function __construct(array $childNodes)
    {
        parent::__construct('alternative', null, $childNodes);
    }
}
