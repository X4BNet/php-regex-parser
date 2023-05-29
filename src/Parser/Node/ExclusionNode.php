<?php

namespace RegexParser\Parser\Node;

use RegexParser\Parser\AbstractNode;
use RegexParser\Parser\NodeInterface;

class ExclusionNode extends AbstractNode
{
    /**
     * @param list<NodeInterface> $childNodes
     */
    public function __construct(array $childNodes)
    {
        parent::__construct('exclusion', null, $childNodes);
    }
}
