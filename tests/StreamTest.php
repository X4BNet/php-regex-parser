<?php

namespace RegexParser\Test;

use RegexParser\Stream;

class StreamTest extends \PHPUnit\Framework\TestCase
{
    /** @var list<string> */
    private array $input;

    /** @var Stream<string> */
    private Stream $stream;

    public function setUp(): void
    {
        $this->input = ['a', 'b', 'k'];
        $this->stream = new Stream($this->input);
    }

    public function testItShouldReturnTheNextDatumWhenICallNextMethod(): void
    {
        $this->assertEquals('a', $this->stream->next());
        $this->assertEquals('b', $this->stream->next());
        $this->assertEquals('k', $this->stream->next());
        $this->assertNull($this->stream->next());
    }

    public function testItShouldReturnADatumRelativeToTheCurrentCursorWhenICallReadAtMethod(): void
    {
        $this->stream->next();
        $this->assertEquals('a', $this->stream->readAt(0));
        $this->stream->next();
        $this->assertEquals('a', $this->stream->readAt(-1));
        $this->assertEquals('k', $this->stream->readAt(1));
    }

    public function testItShouldReturnTrueIfItHasNextDatumFalseOtherwiseWhenICallHasNextMethod(): void
    {
        $this->assertTrue($this->stream->hasNext());
        $this->stream->next();
        $this->assertTrue($this->stream->hasNext());
        $this->stream->next();
        $this->stream->next();
        $this->assertFalse($this->stream->hasNext());
    }

    public function testItShouldReturnTheContentOfTheStreamWhenICallInputMethod(): void
    {
        $this->assertEquals($this->input, $this->stream->input());
    }
}
