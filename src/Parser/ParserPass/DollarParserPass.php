<?php

namespace RegexParser\Parser\ParserPass;

use RegexParser\Lexer\Token;
use RegexParser\Lexer\TokenInterface;
use RegexParser\Parser\AbstractParserPass;
use RegexParser\Parser\Node\EndNode;
use RegexParser\Parser\NodeInterface;
use RegexParser\Parser\Parser;
use RegexParser\Stream;
use RegexParser\StreamInterface;

class DollarParserPass extends AbstractParserPass
{
    public function parseStream(StreamInterface $stream, ?string $parentPass = null): StreamInterface
    {
        $result = [];

        while ($token = $stream->next()) {
            $result[] = $token;
        }

        // Looking for `$` pattern
        if (
            $result[count($result) - 1] instanceof TokenInterface &&
            $result[count($result) - 1]->is('T_DOLLAR') &&
            count($result) > 1
        ) {
            $result[count($result) - 2] = new EndNode(
                $this
                        ->parser
                        ->parseStream(
                            new Stream([$result[count($result) - 2]])
                        )
                        ->input()
            );
            array_pop($result);
        }

        unset($stream);

        return new Stream($result);
    }
}
