<?php
namespace App\Utility\Contract;

interface IFileStorage {
    public function touch(string $filePath): void;
    public function append(string $file, string $content): void;
}