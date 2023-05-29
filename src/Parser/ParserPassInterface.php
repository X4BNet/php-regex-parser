<?php

namespace RegexParser\Parser;

use RegexParser\Lexer\TokenInterface;
use RegexParser\Parser\Exception\ParserException;
use RegexParser\StreamInterface;

interface ParserPassInterface
{
    public function setParser(Parser $parser): void;

    /**
     * @param StreamInterface<TokenInterface|NodeInterface> $stream
     * @return StreamInterface<TokenInterface|NodeInterface>
     * @throws ParserException
     */
    public function parseStream(StreamInterface $stream, ?string $parentPass = null): StreamInterface;

    public function getName(): string;
}
