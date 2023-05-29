<?php

namespace RegexParser\Lexer;

use RegexParser\Stream;

/**
 * @extends Stream<TokenInterface>
 */
class TokenStream extends Stream
{
    protected Lexer $lexer;

    public function __construct(Lexer $lexer)
    {
        $this->lexer = $lexer;
        parent::__construct([]);
    }

    public function next(): ?TokenInterface
    {
        $token = $this->lexer->nextToken();

        if ($token === null) {
            return null;
        }

        $this->input[] = $token;

        return parent::next();
    }

    public function readAt(int $index): ?TokenInterface
    {
        if ($index > 0 && $this->lexer->getStream()->cursor() - $this->cursor < $index) {
            $i = 0;
            while (($token = $this->lexer->nextToken()) && $i < $index) {
                $this->input[] = $token;
                ++$i;
            }
        }

        return parent::readAt($index);
    }

    public function hasNext(): bool
    {
        if ($this->cursor < $this->lexer->getStream()->cursor()) {
            return true;
        }

        return $this->lexer->getStream()->hasNext();
    }
}
