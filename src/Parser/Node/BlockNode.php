<?php

namespace RegexParser\Parser\Node;

use RegexParser\Parser\AbstractNode;
use RegexParser\Parser\NodeInterface;

class BlockNode extends AbstractNode
{
    private bool $isSubPattern;

    private bool $isNonCapture;

    private ?string $captureName;

    /**
     * @param list<NodeInterface> $childNodes
     */
    public function __construct(array $childNodes, bool $isSubPattern = false, bool $isNonCapture = false, ?string $captureName = null)
    {
        parent::__construct('block', $childNodes);

        $this->isSubPattern = $isSubPattern;
        $this->isNonCapture = $isNonCapture;
        $this->captureName = $captureName;
    }

    public function isSubPattern(): bool
    {
        return $this->isSubPattern;
    }

    public function isNonCapture(): bool
    {
        return $this->isNonCapture;
    }

    public function getCaptureName(): ?string
    {
        return $this->captureName;
    }
}
