<?php

namespace RegexParser\Parser;

interface NodeInterface
{
    public function getName(): string;

    /**
     * @return list<NodeInterface>
     */
    public function getChildNodes(): array;

    public function appendChild(NodeInterface $childNode): void;

    public function getParent(): ?NodeInterface;

    public function setParent(NodeInterface $parent): void;
}
