<?php

namespace RegexParser\Generator;

use RegexParser\AbstractGenerator;
use RegexParser\Lexer\EscapeToken;
use RegexParser\Parser\Exception\ParserException;
use RegexParser\Parser\Node\AlternativeNode;
use RegexParser\Parser\Node\BeginNode;
use RegexParser\Parser\Node\BlockNode;
use RegexParser\Parser\Node\CharacterClassNode;
use RegexParser\Parser\Node\EndNode;
use RegexParser\Parser\Node\RepetitionNode;
use RegexParser\Parser\Node\TokenNode;
use RegexParser\Parser\NodeInterface;
use RegexParser\Parser\Parser;

class RandomGenerator extends AbstractGenerator
{
    /**
     * @throws ParserException
     */
    public static function create(string $pattern): self
    {
        $parser = Parser::create();

        return new self($parser->parse($pattern));
    }

    public function generate(?int $seed = null): string
    {
        if ($seed !== null) {
            mt_srand($seed);
        }

        try {
            $output = '';

            foreach ($this->ast->getChildNodes() as $childNode) {
                $output .= $this->printNode($childNode);
            }

            return $output;
        } finally {
            if ($seed !== null) {
                mt_srand();
            }
        }
    }

    protected function printNode(NodeInterface $node): ?string
    {
        if ($node instanceof AlternativeNode) {
            return $this->printAlternativeNode($node);
        } elseif ($node instanceof BlockNode) {
            return $this->printBlockNode($node);
        } elseif ($node instanceof CharacterClassNode) {
            return $this->printCharacterClassNode($node);
        } elseif ($node instanceof RepetitionNode) {
            return $this->printRepetitionNode($node);
        } elseif ($node instanceof TokenNode) {
            return $this->printTokenNode($node);
        } elseif ($node instanceof BeginNode) {
            return $this->printBeginNode($node);
        } elseif ($node instanceof EndNode) {
            return $this->printEndNode($node);
        }

        return null;
    }

    protected function printAlternativeNode(AlternativeNode $node): string
    {
        $childNodes = $node->getChildNodes();

        return $this->printNode($childNodes[mt_rand(0, count($childNodes) - 1)]);
    }

    protected function printBlockNode(BlockNode $node): string
    {
        $childNodes = $node->getChildNodes();

        if ($node->isSubPattern()) {
            $output = '';

            foreach ($childNodes as $childNode) {
                $output .= $this->printNode($childNode);
            }

            return $output;
        }

        return $this->printNode($childNodes[mt_rand(0, count($childNodes) - 1)]);
    }

    protected function printBeginNode(BeginNode $node): string
    {
        $childNodes = $node->getChildNodes();
        $output = '';

        foreach ($childNodes as $childNode) {
            $output .= $this->printNode($childNode);
        }

        return $output;
    }

    protected function printEndNode(EndNode $node): string
    {
        $childNodes = $node->getChildNodes();
        $output = '';

        foreach ($childNodes as $childNode) {
            $output .= $this->printNode($childNode);
        }

        return $output;
    }

    protected function printCharacterClassNode(CharacterClassNode $node): string
    {
        $range = range($node->getStart()->getValue()->getValue(), $node->getEnd()->getValue()->getValue());

        return $range[mt_rand(0, count($range) - 1)];
    }

    protected function printRepetitionNode(RepetitionNode $node): string
    {
        if ($node->getMax() !== null) {
            $count = mt_rand($node->getMin(), $node->getMax());
        } else {
            $count = mt_rand($node->getMin(), $node->getMin() + 5);
        }

        $output = '';

        for ($i = 0; $i < $count; ++$i) {
            foreach ($node->getChildNodes() as $childNode) {
                $output .= $this->printNode($childNode);
            }
        }

        return $output;
    }

    protected function printTokenNode(TokenNode $node): string
    {
        $token = $node->getValue();

        if ($token instanceof EscapeToken) {
            // Not supported yet
            return '';
        }

        $parent = $node->getParent();
        if (
            $token->is('T_PERIOD') &&
             (!($parent instanceof BlockNode) || (/*$parent instanceof BlockNode &&*/ $parent->isSubPattern()))
        ) {
            $range = range('a', 'Z');

            return $range[mt_rand(0, count($range) - 1)];
        }

        return $token->getValue();
    }
}
