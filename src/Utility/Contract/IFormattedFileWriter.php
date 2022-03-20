<?php
namespace App\Utility\Contract;

interface IFormattedFileWriter {
    /**
     * @param array<string> $data 
     * @return void 
     */
    public function write(array $data): void;
}