<?php
namespace App\Utility\Contract;

interface IFormattedFileWriter {
    /**
     * @param array<string> $data 
     * @return void 
     */
    public function write(array $data): void;

    /**
     * @param array<string> $header 
     * @return IFormattedFileWriter 
     */
    public function setHeader(array $header): self;
}