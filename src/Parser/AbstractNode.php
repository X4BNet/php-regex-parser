<?php

namespace RegexParser\Parser;

abstract class AbstractNode implements NodeInterface
{
    /**
     * @var list<NodeInterface>
     */
    protected array $childNodes = [];

    protected string $name;

    /**
     * @var null|mixed
     */
    protected $value = null;

    protected ?NodeInterface $parent = null;

    /**
     * @param mixed $value
     * @param list<NodeInterface> $childNodes
     */
    public function __construct(string $name, $value, array $childNodes = [])
    {
        $this->name = $name;
        $this->value = $value;
        $this->childNodes = $childNodes;

        foreach ($this->childNodes as $childNode) {
            $childNode->setParent($this);
        }
    }

    public function getParent(): ?NodeInterface
    {
        return $this->parent;
    }

    public function setParent(NodeInterface $parent): void
    {
        $this->parent = $parent;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return list<NodeInterface>
     */
    public function getChildNodes(): array
    {
        return $this->childNodes;
    }

    public function appendChild(NodeInterface $childNode): void
    {
        $this->childNodes[] = $childNode;
    }
}
