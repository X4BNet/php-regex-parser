<?php

namespace RegexParser\Test;

use RegexParser\Lexer\StringStream;

class StringStreamTest extends \PHPUnit\Framework\TestCase
{
    public function testItShouldConvertTheInputToArray(): void
    {
        $stream = new StringStream('abc');
        $this->assertEquals(array('a', 'b', 'c'), $stream->input());
    }
}
