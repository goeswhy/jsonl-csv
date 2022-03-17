<?php
namespace App\Utility\Contract;

use App\Utility\ParsedChunk;

interface IChunkParser {
    /**
     * Parse chunk data to ParsedChunk
     * 
     * @param string $currentChunk 
     * @param string|null $previousChunk 
     * @return ParsedChunk
     */
    public function parse(string $currentChunk, string|null $previousChunk = null): ParsedChunk;
}