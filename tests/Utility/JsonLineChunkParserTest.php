<?php
namespace App\Tests\Utility;

use App\Utility\JsonLineChunkParser;
use PHPUnit\Framework\TestCase;

class JsonLineChunkParserTest extends TestCase
{
    private JsonLineChunkParser $parser ;

    protected function setUp(): void
    {
        $this->parser = new JsonLineChunkParser();
    }

    public function jsonLineDataProvider(): array {
        $defaultExpected = [
            [
                'product_id' => 1,
                'name' => 'lorem',
            ],
            [
                'product_id' => 2,
                'name' => 'ipsum',
            ]
        ];

        return [
            [
                "{\"product_id\": 1, \"name\": \"lorem\"}\n{\"product_id\": 2, \"name\": \"ipsum\"}",
                $defaultExpected,
                NULL
            ],
            [
                "{\"product_id\": 1, \"name\": \"lorem\"}\n{\"product_id\": 2",
                [
                    [ 'product_id' => 1, 'name' => 'lorem'],
                ],
                "{\"product_id\": 2",
            ],
            [
                "{\"product_id\": 1, \"name\": \"lorem\"}\nINVALID_JSON\n{\"product_id\": 2, \"name\": \"ipsum\"}",
                $defaultExpected,
                NULL
            ],
        ];
    }

    /**
     * @testdox Test parse output and next chunk if exists
     * @dataProvider jsonLineDataProvider
     */
    public function testWithoutNextChunkAsInput(string $input, array $parsed, ?string $unparsed) {
        $actual = $this->parser->parse($input);
        $this->assertEquals($parsed, $actual->getParsed());
        $this->assertEquals($unparsed, $actual->getUnparsed());
    }

    public function jsonLineDataWithPreviousChunkProvider(): array {
        return [
            [
                "\"lorem\"}\n{\"product_id\": 2, \"name\": \"ipsum\"}",
                "{\"product_id\": 1, \"name\": ",
                [
                    [
                        'product_id' => 1,
                        'name' => 'lorem',
                    ],
                    [
                        'product_id' => 2,
                        'name' => 'ipsum',
                    ]
                ],
                NULL,
            ],
            [
                "\n{\"product_id\": 2, \"name\": \"ipsum\"}",
                "INVALID_JSON",
                [
                    [
                        'product_id' => 2,
                        'name' => 'ipsum',
                    ]
                ],
                NULL,
            ],
        ];
    }

    /**
     * @testdox Test parse output and previous
     * @dataProvider jsonLineDataWithPreviousChunkProvider
     */
    public function testWithPreviousChunkAsInput(string $input, string $previous, array $parsed, ?string $unparsed) {
        $actual = $this->parser->parse($input, $previous);
        $this->assertEquals($parsed, $actual->getParsed());
        $this->assertEquals($unparsed, $actual->getUnparsed());
    }
}
