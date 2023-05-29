<?php

namespace RegexParser\Test;

use RegexParser\Lexer\StringStream;

class StringStreamTest extends \PHPUnit\Framework\TestCase
{
    private $input;

    private $protected;

    public function setUp(): void
    {
        $this->input = 'abc';
        $this->stream = new StringStream($this->input);
    }

    public function testItShouldConvertTheInputToArray()
    {
        $this->assertEquals(array('a', 'b', 'c'), $this->stream->input(), $this->stream->next());
    }
}
