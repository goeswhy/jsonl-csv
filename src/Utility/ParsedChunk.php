<?php
namespace App\Utility;

class ParsedChunk {
    public function __construct(private array $parsed, private ?string $unparsed) {}

    public function getParsed(): array {
        return $this->parsed;
    }

    public function getUnparsed(): ?string {
        return $this->unparsed;
    }
}