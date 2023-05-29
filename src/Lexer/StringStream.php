<?php

namespace RegexParser\Lexer;

use RegexParser\Stream;
use RegexParser\StreamInterface;

/**
 * @extends Stream<string>
 */
class StringStream extends Stream
{
    /**
     * @param string $input
     */
    public function __construct(string $input)
    {
        $len = mb_strlen($input);
        $array = [];
        while ($len) {
            $array[] = mb_substr($input, 0, 1);
            $input = mb_substr($input, 1, $len);
            $len = mb_strlen($input);
        }

        parent::__construct($array);
    }
}
