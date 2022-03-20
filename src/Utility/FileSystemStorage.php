<?php
namespace App\Utility;

use App\Utility\Contract\IFileStorage;
use Symfony\Component\Filesystem\Filesystem;

class FileSystemStorage implements IFileStorage {
    public function __construct(private Filesystem $storage) {}

    public function touch(string $filePath): void {
        $this->storage->touch($filePath);
    }

    public function append(string $file, string $content): void {
        $this->storage->appendToFile($file, $content);
    }
}
