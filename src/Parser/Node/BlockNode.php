<?php

namespace RegexParser\Parser\Node;

use RegexParser\Parser\AbstractNode;
use RegexParser\Parser\NodeInterface;

class BlockNode extends AbstractNode
{
    /**
     * @var bool
     */
    protected bool $isSubPattern;

    /**
     * @param list<NodeInterface> $childNodes
     */
    public function __construct(array $childNodes, bool $isSubPattern = false)
    {
        parent::__construct('block', null, $childNodes);

        $this->isSubPattern = $isSubPattern;
    }

    public function isSubPattern(): bool
    {
        return $this->isSubPattern;
    }
}
