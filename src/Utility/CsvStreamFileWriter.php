<?php
namespace App\Utility;

use App\Utility\Contract\IFileStorage;
use App\Utility\Contract\IFormattedFileWriter;
use App\Utility\Exception\CsvStreamWriteException;

class CsvStreamFileWriter implements IFormattedFileWriter {
    const SEPARATOR = ';';
    const ENDLINE = "\n";

    private array $header;

    public function __construct(private IFileStorage $storage, private string $filename) {
        $this->init($filename);
    }

    private function init(string $filename): void {
        $this->storage->touch($filename);
    }

    public function setHeader(array $header): self
    {
        $this->header = $header;
        return $this;
    }

    public function write(array $data): void
    {
        if (empty($this->header)) {
            throw new CsvStreamWriteException('Write failed, empty header');
        }

        // TODO show log on empty data was given
        if (count($data) === 0) return;

        if (count($data) !== count($this->header)) {
            throw new CsvStreamWriteException('Write failed, invalid header column length');
        }

        $this->storage->append($this->filename, $this->toCsv($data));
    }

    private function toCsv(array $data): string {
        return implode(self::SEPARATOR, $data) . self::ENDLINE;
    }
}
