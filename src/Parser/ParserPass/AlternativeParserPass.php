<?php

namespace RegexParser\Parser\ParserPass;

use RegexParser\Lexer\Token;
use RegexParser\Lexer\TokenInterface;
use RegexParser\Parser\AbstractParserPass;
use RegexParser\Parser\Node\AlternativeNode;
use RegexParser\Parser\Node\TokenNode;
use RegexParser\Stream;
use RegexParser\StreamInterface;
use RegexParser\Parser\Exception\ParserException;

class AlternativeParserPass extends AbstractParserPass
{
    /**
     * @throws ParserException
     */
    public function parseStream(StreamInterface $stream, ?string $parentPass = null): StreamInterface
    {
        $result = array();

        while ($token = $stream->next()) {
            if (!($token instanceof TokenInterface)) {
                $result[] = $token;
                continue;
            }

            // Looking for `*|*` pattern
            if ($token->is('T_PIPE')) {
                if ($stream->cursor() < 1) {
                    $previous = new TokenNode(new Token('T_CHAR', ''));
                } else {
                    if ($result[count($result) - 1] instanceof AlternativeNode) {
                        if (($next = $stream->next()) instanceof TokenInterface) {
                            $result[count($result) - 1]->appendChild(new TokenNode($next));
                        } else {
                            assert($next !== null);
                            $result[count($result) - 1]->appendChild($next);
                        }
                        continue;
                    }
                    // Remove previous
                    array_pop($result);

                    $previous = $stream->readAt(-1);
                    if ($previous instanceof TokenInterface) {
                        $previous = new TokenNode($previous);
                    }
                }
                assert($previous !== null);

                if($stream->hasNext()) {
                    $next = $stream->next();
                    if ($next instanceof TokenInterface) {
                        $next = new TokenNode($next);
                    }
                } else {
                    $next = new TokenNode(new Token('T_CHAR', ''));
                }
                assert($next !== null);

                $result[] = new AlternativeNode([$previous, $next]);
            } else {
                $result[] = $token;
            }
        }

        unset($stream);

        return new Stream($result);
    }
}
