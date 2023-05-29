<?php

namespace RegexParser\Test\Parser;

use DOMDocument;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RegexParser\Formatter\XMLFormatter;
use RegexParser\Parser\Parser;

class ParserTest extends \PHPUnit\Framework\TestCase
{
    protected Parser $parser;

    protected XMLFormatter $formatter;

    public function setUp(): void
    {
        $this->parser = Parser::create();
        $this->formatter = new XMLFormatter();
    }

    /**
     * @dataProvider patternProvider
     */
    public function testPattern(string $input, string $expectedOutput, string $filename): void
    {
        $expectedOutputDOM = new DOMDocument('1.0', 'utf-8');
        $expectedOutputDOM->preserveWhiteSpace = false;
        $expectedOutputDOM->formatOutput = false;
        $expectedOutputDOM->loadXML($expectedOutput);

        $ast = $this->parser->parse($input);
        $xml = $this->formatter->format($ast);

        $this->assertEquals(
            $expectedOutputDOM->saveXML(),
            $xml->saveXML(),
            sprintf('%s does not match the generated xml', $filename)
        );
    }

    /**
     * @return list<array{string, string, string}>
     */
    public function patternProvider(): array
    {
        $data = array();

        $dirIterator = new RecursiveDirectoryIterator(__DIR__ . '/../fixture/pattern');
        $iterator = new RecursiveIteratorIterator($dirIterator, RecursiveIteratorIterator::CHILD_FIRST);

        foreach ($iterator as $file) {
            assert($file instanceof \SplFileInfo);

            if (!$file->isFile() || $file->getExtension() !== 'txt') {
                continue;
            }

            $content = file_get_contents($file->getPathName());
            assert($content != false);

            $entry = array_map('trim', explode('----', $content, 2));
            assert(count($entry) === 2);

            $entry[] = $file->getFilename();
            $data[] = $entry;
        }

        return $data;
    }
}
