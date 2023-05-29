<?php

namespace RegexParser\Formatter;

use DomDocument;
use RegexParser\AbstractFormatter;
use RegexParser\Lexer\EscapeToken;
use RegexParser\Parser\Node\AlternativeNode;
use RegexParser\Parser\Node\ASTNode;
use RegexParser\Parser\Node\BeginNode;
use RegexParser\Parser\Node\BlockNode;
use RegexParser\Parser\Node\CharacterClassNode;
use RegexParser\Parser\Node\EndNode;
use RegexParser\Parser\Node\ExclusionNode;
use RegexParser\Parser\Node\RepetitionNode;
use RegexParser\Parser\Node\TokenNode;
use RegexParser\Parser\NodeInterface;

class XMLFormatter extends AbstractFormatter
{
    /**
     * @var DomDocument
     */
    protected DomDocument $document;

    public function format(NodeInterface $ast): \DomDocument
    {
        $this->document = new DomDocument('1.0', 'utf-8');
        $this->document->appendChild($this->formatNode($ast));

        return $this->document;
    }

    protected function createXmlNode(string $name, ?string $value = null): \DOMElement
    {
        if ($value !== null) {
            return $this->document->createElement($name, $value);
        }

        return $this->document->createElement($name);
    }

    protected function formatNode(NodeInterface $node): \DOMElement
    {
        if ($node instanceof ASTNode) {
            $xmlNode = $this->formatASTNode($node);
        } elseif ($node instanceof TokenNode) {
            $xmlNode = $this->formatTokenNode($node);
        } elseif ($node instanceof AlternativeNode) {
            $xmlNode = $this->formatDefaultNode($node);
        } elseif ($node instanceof BlockNode) {
            $xmlNode = $this->formatBlockNode($node);
        } elseif ($node instanceof CharacterClassNode) {
            $xmlNode = $this->formatCharacterClassNode($node);
        } elseif ($node instanceof RepetitionNode) {
            $xmlNode = $this->formatRepetitionNode($node);
        } elseif ($node instanceof ExclusionNode) {
            $xmlNode = $this->formatDefaultNode($node);
        } elseif ($node instanceof BeginNode) {
            $xmlNode = $this->formatDefaultNode($node);
        } elseif ($node instanceof EndNode) {
            $xmlNode = $this->formatDefaultNode($node);
        } else {
            throw new \RuntimeException('Unknown xml node');
        }

        foreach ($node->getChildNodes() as $childNode) {
            $xmlNode->appendChild($this->formatNode($childNode));
        }

        return $xmlNode;
    }

    protected function formatASTNode(ASTNode $node): \DOMElement
    {
        return $this->createXmlNode('ast');
    }

    protected function formatTokenNode(TokenNode $node): \DOMElement
    {
        $token = $node->getValue();
        $xmlNode = $this->createXmlNode('token', $token->getValue());
        $xmlNode->setAttribute('type', str_replace('_', '-', strtolower(substr($token->getName(), 2))));

        if ($token instanceof EscapeToken) {
            $xmlNode->setAttribute('exclusion-sequence', $token->isExclusionSequence() ? 'true' : 'false');
        }

        return $xmlNode;
    }

    protected function formatBlockNode(BlockNode $node): \DOMElement
    {
        $xmlNode = $this->createXmlNode($node->getName());
        $xmlNode->setAttribute('sub-pattern', $node->isSubPattern() ? 'true' : 'false');

        return $xmlNode;
    }

    protected function formatCharacterClassNode(CharacterClassNode $node): \DOMElement
    {
        $xmlNode = $this->createXmlNode($node->getName());
        $xmlNode->appendChild($this->formatNode($node->getStart()));
        $xmlNode->appendChild($this->formatNode($node->getEnd()));

        return $xmlNode;
    }

    protected function formatRepetitionNode(RepetitionNode $node): \DOMElement
    {
        $xmlNode = $this->createXmlNode($node->getName());
        $xmlNode->setAttribute('min', sprintf("%d", $node->getMin()));

        if ($node->getMax() !== null) {
            $xmlNode->setAttribute('max', sprintf("%d", $node->getMax()));
        }

        return $xmlNode;
    }

    protected function formatDefaultNode(NodeInterface $node): \DOMElement
    {
        return $this->createXmlNode($node->getName());
    }
}
