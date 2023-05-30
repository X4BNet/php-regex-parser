<?php

namespace RegexParser\Parser\ParserPass;

use RegexParser\Lexer\TokenInterface;
use RegexParser\Parser\AbstractParserPass;
use RegexParser\Parser\Exception\ParserException;
use RegexParser\Parser\Node\BlockNode;
use RegexParser\Stream;
use RegexParser\StreamInterface;

class ParenthesisBlockParserPass extends AbstractParserPass
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
        $isNonCapture = false;
        $captureName = null;

        while ($token = $stream->next()) {
            if (!($token instanceof TokenInterface)) {
                if ($blocksFound === 0) {
                    $result[] = $token;
                } else {
                    $stack[] = $token;
                }
                continue;
            }

            // Looking for `(` pattern
            if ($token->is('T_LEFT_PARENTHESIS')) {
                $blocksFound++;

                // Null capture group
                if (
                    ($tmp = $stream->readAt(1)) instanceof TokenInterface &&
                    $tmp->is('T_QUESTION') &&
                    ($tmp = $stream->readAt(2)) instanceof TokenInterface &&
                    $tmp->is('T_COLON')
                ) {
                    $stream->next();
                    $stream->next();
                    $isNonCapture = true;
                }

                // named capture group
                if (
                    ($tmp = $stream->readAt(1)) instanceof TokenInterface &&
                    $tmp->is('T_QUESTION')
                ) {
                    $next1 = $stream->readAt(2);
                    $next2 = $stream->readAt(3);
                    $isNamedCapture = false;

                    if ($next1 instanceof TokenInterface && $next1->is('T_LOWER')) {
                        // (?<FOO>)
                        $stream->next();
                        $stream->next();
                        $isNamedCapture = true;
                    } elseif (
                        $next1 instanceof TokenInterface &&
                        $next1->is('T_CHAR') &&
                        $next1->getValue() === 'P' &&
                        $next2 instanceof TokenInterface &&
                        $next2->is('T_LOWER')
                    ) {
                        // (?P<FOO>)
                        $stream->next();
                        $stream->next();
                        $stream->next();
                        $isNamedCapture = true;
                    }

                    if ($isNamedCapture) {
                        $buf = '';
                        while (null !== ($tmp = $stream->next()) && $tmp instanceof TokenInterface && !$tmp->is('T_GREATER')) {
                            if ($tmp->is('T_CHAR') || $tmp->is('T_UNDERSCORE')) {
                                $buf .= $tmp->getValue();
                            } else {
                                throw new ParserException('Invalid character in named capture group: ' . $tmp->getValue());
                            }
                        }

                        if ($stream->readAt(1) === null) {
                            throw new ParserException("Unterminated capture group name");
                        }

                        $captureName = $buf;
                    }
                }

                if ($blocksFound > 1) {
                    // We matched a nested parenthesis so we ignore it
                    $stack[] = $token;
                }
            } elseif ($blocksFound > 0 && $token->is('T_RIGHT_PARENTHESIS')) {
                if ($blocksFound === 1) {
                    $result[] = new BlockNode(
                        $this
                            ->parser
                            ->parseStream(new Stream($stack))
                            ->input(),
                        true,
                        $isNonCapture,
                        $captureName
                    );
                    $stack = [];
                } else {
                    $stack[] = $token;
                }
                $blocksFound--;
            } elseif ($blocksFound > 0) {
                $stack[] = $token;
            } elseif ($blocksFound === 0 && $token->is('T_RIGHT_PARENTHESIS')) {
                throw new ParserException('Parenthesis block not opened');
            } else {
                $result[] = $token;
            }
        }

        if ($blocksFound > 0) {
            throw new ParserException('Parenthesis block not closed');
        }

        unset($stream);

        return new Stream($result);
    }
}
