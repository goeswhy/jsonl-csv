<?php
namespace App\Utility;

use App\Utility\ParsedChunk;
use App\Utility\Contract\IChunkParser;

class JsonLineChunkParser implements IChunkParser {
    const LINE_SEPARATOR = "\n";

    public function parse(string $currentChunk, ?string $previousChunk = null): ParsedChunk {
        $lines = explode(self::LINE_SEPARATOR, $previousChunk.$currentChunk);
        $jsonLines = array_map(fn($line) => json_decode($line, true), $lines);
        $parsed = [];
        $unparsed = NULL;
        foreach($jsonLines as $lineNumber => $data) {
            if (!is_null($data)) {
                $parsed[] = $data;
                continue;
            }

            // Only proceed with invalid last
            if ($lineNumber == count($lines) - 1) {
                $unparsed = $lines[$lineNumber];
            }
        }

        return new ParsedChunk($parsed, $unparsed);
    }
}
