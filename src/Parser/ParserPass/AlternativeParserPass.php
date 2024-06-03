<?php

namespace RegexParser\Parser\ParserPass;

use RegexParser\Lexer\Token;
use RegexParser\Lexer\TokenInterface;
use RegexParser\Parser\AbstractParserPass;
use RegexParser\Parser\Node\AlternativeNode;
use RegexParser\Parser\Node\BlockNode;
use RegexParser\Parser\Node\TokenNode;
use RegexParser\Parser\NodeInterface;
use RegexParser\Stream;
use RegexParser\StreamInterface;
use RegexParser\Parser\Exception\ParserException;

class AlternativeParserPass extends AbstractParserPass
{
    private function block($stack): BlockNode
    {
        $parts = $this
            ->parser
            ->parseStream(
                new Stream($stack),
                'AlternativeParserPass',
                []
            )
            ->input();
        return new BlockNode($parts, true);
    }
    /**
     * @throws ParserException
     */
    public function parseStream(StreamInterface $stream, ?string $parentPass = null): StreamInterface
    {
        $result = null;
        $parts = array();

        while ($token = $stream->next()) {
            if (!($token instanceof TokenInterface)) {
                $parts[] = $token;
                continue;
            }

            // Looking for `*|*` pattern
            if ($token->is('T_PIPE')) {
                if(empty($parts)) {
                    $previous = new BlockNode([new TokenNode(new Token('T_CHAR', ''))]);
                } else {
                    $previous = $this->block($parts);
                    $parts = [];
                }

                if(!$result){
                    $result = new AlternativeNode([$previous]);
                } else {
                    $result->appendChild($previous);
                }
            } else {
                $parts[] = new TokenNode($token);
            }
        }

        unset($stream);

        if(empty($result)) {
            return new Stream($parts);
        }

        $result->appendChild($this->block($parts));
        return new Stream([$result]);
    }
}
