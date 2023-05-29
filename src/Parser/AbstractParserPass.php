<?php

namespace RegexParser\Parser;

abstract class AbstractParserPass implements ParserPassInterface
{
    protected Parser $parser;

    public function setParser(Parser $parser): void
    {
        $this->parser = $parser;
    }

    public function getName(): string
    {
        $className = explode('\\', get_class($this));

        return array_pop($className);
    }
}
