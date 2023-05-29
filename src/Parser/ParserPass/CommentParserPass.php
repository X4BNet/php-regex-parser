<?php

namespace RegexParser\Parser\ParserPass;

use RegexParser\Lexer\TokenInterface;
use RegexParser\Parser\AbstractParserPass;
use RegexParser\Parser\Exception\ParserException;
use RegexParser\Parser\NodeInterface;
use RegexParser\Stream;
use RegexParser\StreamInterface;

class CommentParserPass extends AbstractParserPass
{
    public function parseStream(StreamInterface $stream, ?string $parentPass = null): StreamInterface
    {
        $commentFound = false;
        $stack = [];
        /** @var list<TokenInterface> $result */
        $result = [];

        while ($token = $stream->next()) {
            if ($stream->cursor() < 2 || !($token instanceof TokenInterface)) {
                $result[] = $token;
                continue;
            }

            // Looking for `(?#` pattern
            if (
                $token->is('T_POUND') &&
                ($tmp = $stream->readAt(-1)) instanceof TokenInterface &&
                $tmp->is('T_QUESTION') &&
                ($tmp = $stream->readAt(-2)) instanceof TokenInterface &&
                $tmp->is('T_LEFT_PARENTHESIS') &&
                !$commentFound
            ) {
                $commentFound = true;

                // We remove (? from result
                array_pop($result);
                array_pop($result);
            } elseif ($commentFound && $token->is('T_RIGHT_PARENTHESIS')) {
                // $stack contains our comment but we don't keep it
                $commentFound = false;
                $stack = array();
            } elseif ($commentFound) {
                $stack[] = $token;
            } else {
                $result[] = $token;
            }
        }

        if ($commentFound) {
            throw new ParserException('Comment not closed');
        }

        unset($stream);

        return new Stream($result);
    }
}
