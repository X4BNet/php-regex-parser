<?php

namespace RegexParser\Parser\ParserPass;

use RegexParser\Lexer\TokenInterface;
use RegexParser\Parser\AbstractParserPass;
use RegexParser\Parser\Exception\ParserException;
use RegexParser\Parser\Node\BlockNode;
use RegexParser\Parser\Parser;
use RegexParser\Stream;
use RegexParser\StreamInterface;

class BracketBlockParserPass extends AbstractParserPass
{
    /**
     * @throws ParserException
     */
    public function parseStream(StreamInterface $stream, ?string $parentPass = null): StreamInterface
    {
        /** @var int $blocksFound */
        $blocksFound = 0;
        /** @var list<TokenInterface> $stack */
        $stack = [];
        $result = array();

        while ($token = $stream->next()) {
            if (!($token instanceof TokenInterface)) {
                if ($blocksFound === 0) {
                    $result[] = $token;
                } else {
                    $stack[] = $token;
                }
                continue;
            }

            // Looking for `[` pattern
            if ($token->is('T_LEFT_BRACKET')) {
                ++$blocksFound;

                if ($blocksFound > 1) {
                    // We matched a nested parenthesis so we ignore it
                    $stack[] = $token;
                }
            } elseif ($blocksFound > 0 && $token->is('T_RIGHT_BRACKET')) {
                if ($blocksFound === 1) {
                    $result[] = new BlockNode(
                        $this
                            ->parser
                            ->parseStream(
                                new Stream($stack),
                                'BracketBlockParserPass',
                                ['BracketBlockParserPass']
                            )
                            ->input()
                    );
                    $stack = [];
                } else {
                    $stack[] = $token;
                }
                --$blocksFound;
            } elseif ($blocksFound > 0) {
                $stack[] = $token;
            } elseif ($blocksFound === 0 && $token->is('T_RIGHT_BRACKET')) {
                throw new ParserException('Bracket block not opened');
            } else {
                $result[] = $token;
            }
        }

        if ($blocksFound > 0) {
            throw new ParserException('Bracket block not closed');
        }

        unset($stream);

        return new Stream($result);
    }
}
