<?php

namespace RegexParser\Parser;

use RegexParser\Lexer\Lexer;
use RegexParser\Lexer\TokenInterface;
use RegexParser\Lexer\TokenStream;
use RegexParser\Parser\Exception\ParserException;
use RegexParser\Parser\Node\ASTNode;
use RegexParser\Parser\ParserPass\AlternativeParserPass;
use RegexParser\Parser\ParserPass\BracketBlockParserPass;
use RegexParser\Parser\ParserPass\CharacterClassParserPass;
use RegexParser\Parser\ParserPass\CommentParserPass;
use RegexParser\Parser\ParserPass\DollarParserPass;
use RegexParser\Parser\ParserPass\HatParserPass;
use RegexParser\Parser\ParserPass\ParenthesisBlockParserPass;
use RegexParser\Parser\ParserPass\RepetitionParserPass;
use RegexParser\Parser\ParserPass\TokenParserPass;
use RegexParser\StreamInterface;

class Parser
{
    /**
     * @var list<ParserPassInterface>
     */
    protected array $parserPasses = [];

    public static function create(): self
    {
        $parser = new self();
        $parser->registerParserPass(new CommentParserPass()); // will remove all comments
        $parser->registerParserPass(new BracketBlockParserPass());
        $parser->registerParserPass(new ParenthesisBlockParserPass());
        $parser->registerParserPass(new CharacterClassParserPass());
        $parser->registerParserPass(new DollarParserPass());
        $parser->registerParserPass(new HatParserPass());
        $parser->registerParserPass(new AlternativeParserPass());
        $parser->registerParserPass(new RepetitionParserPass()); // must be the last one just before dollar pass
        $parser->registerParserPass(new TokenParserPass()); // must be the last one just before token pass

        return $parser;
    }

    public function registerParserPass(ParserPassInterface $parserPass): void
    {
        $parserPass->setParser($this);
        $this->parserPasses[] = $parserPass;
    }

    /**
     * @param StreamInterface<TokenInterface|NodeInterface> $stream
     * @return StreamInterface<NodeInterface>
     * @param list<string> $excludedPasses
     * @throws ParserException
     */
    public function parseStream(StreamInterface $stream, ?string $parentPass = null, array $excludedPasses = []): StreamInterface
    {
        foreach ($this->parserPasses as $parserPass) {
            if (!in_array($parserPass->getName(), $excludedPasses)) {
                $stream = $parserPass->parseStream($stream, $parentPass);
            }
        }

        return self::assertNodeStream($stream);
    }

    /**
     * @throws ParserException
     */
    public function parse(string $input): ASTNode
    {
        $lexer = Lexer::create($input);
        $outputStream = $this->parseStream(new TokenStream($lexer));

        return new ASTNode($outputStream->input());
    }

    /**
     * @param mixed $stream
     * @phpstan-assert StreamInterface<TokenInterface> $stream
     * @return StreamInterface<TokenInterface>
     */
    public static function assertTokenStream($stream): StreamInterface
    {
        assert($stream instanceof StreamInterface);

        foreach ($stream->input() as $item) {
            assert($item instanceof TokenInterface);
        }

        return $stream;
    }

    /**
     * @param mixed $stream
     * @phpstan-assert StreamInterface<NodeInterface> $stream
     * @return StreamInterface<NodeInterface>
     */
    public static function assertNodeStream($stream): StreamInterface
    {
        assert($stream instanceof StreamInterface);

        foreach ($stream->input() as $item) {
            assert($item instanceof NodeInterface);
        }

        return $stream;
    }
}
