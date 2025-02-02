<?php

namespace RegexParser\Parser\ParserPass;

use RegexParser\Lexer\TokenInterface;
use RegexParser\Parser\AbstractParserPass;
use RegexParser\Parser\Node\TokenNode;
use RegexParser\Parser\NodeInterface;
use RegexParser\Stream;
use RegexParser\StreamInterface;

class TokenParserPass extends AbstractParserPass
{
    public function parseStream(StreamInterface $stream, ?string $parentPass = null): StreamInterface
    {
        $result = array();

        while ($token = $stream->next()) {
            if ($token instanceof TokenInterface) {
                $token = new TokenNode($token);
            }

            $result[] = $token;
        }

        unset($stream);

        return new Stream($result);
    }
}
