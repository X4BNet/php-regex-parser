<?php

namespace RegexParser\Parser\ParserPass;

use RegexParser\Lexer\TokenInterface;
use RegexParser\Parser\AbstractParserPass;
use RegexParser\Parser\Node\BeginNode;
use RegexParser\Parser\Node\ExclusionNode;
use RegexParser\Parser\Parser;
use RegexParser\Stream;
use RegexParser\StreamInterface;

class HatParserPass extends AbstractParserPass
{
    public function parseStream(StreamInterface $stream, ?string $parentPass = null): StreamInterface
    {
        $result = array();

        while ($token = $stream->next()) {
            if (!($token instanceof TokenInterface)) {
                $result[] = $token;
                continue;
            }

            // Looking for `^` pattern
            if ($token->is('T_HAT') && $stream->cursor() === 0) {
                if ($parentPass === 'BracketBlockParserPass') {
                    $childNodes = $stream->input();
                    array_shift($childNodes); // Remove ^

                    return new Stream([
                        new ExclusionNode(
                            $this
                                ->parser
                                ->parseStream(new Stream($childNodes), 'BracketBlockParserPass', ['BracketBlockParserPass'])
                                ->input()
                        )
                    ]);
                }

                $next = $stream->next();
                assert($next !== null);

                $result[] = new BeginNode(
                    $this
                            ->parser
                            ->parseStream(new Stream([$next]))
                            ->input()
                );
            } else {
                $result[] = $token;
            }
        }

        unset($stream);

        return new Stream($result);
    }
}
