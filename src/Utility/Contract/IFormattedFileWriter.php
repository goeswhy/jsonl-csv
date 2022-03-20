<?php
namespace App\Utility\Contract;

interface IFormattedFileWriter {
    public function write(array $data): void;
}