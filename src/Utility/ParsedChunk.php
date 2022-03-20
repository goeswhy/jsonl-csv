<?php
namespace App\Utility;

class ParsedChunk {
    /**
     * @param array<string> $parsed 
     * @param null|string $unparsed 
     * @return void 
     */
    public function __construct(private array $parsed, private ?string $unparsed) {}

    /**
     * @return array<string>
     */
    public function getParsed(): array {
        return $this->parsed;
    }

    public function getUnparsed(): ?string {
        return $this->unparsed;
    }
}