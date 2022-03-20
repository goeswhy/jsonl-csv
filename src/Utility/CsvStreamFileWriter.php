<?php
namespace App\Utility;

use App\Utility\Contract\IFileStorage;
use App\Utility\Contract\IFormattedFileWriter;
use App\Utility\Exception\CsvStreamWriteException;

class CsvStreamFileWriter implements IFormattedFileWriter {
    const SEPARATOR = ';';
    const ENDLINE = "\n";

    /**
     * @var array<string>
     */
    private array $header = [];

    public function __construct(private IFileStorage $storage, private string $outputFilename) {
        $this->init($outputFilename);
    }

    private function init(string $filename): void {
        $this->storage->touch($filename);
    }

    /**
     * 
     * @param array<string> $header 
     * @return CsvStreamFileWriter 
     */
    public function setHeader(array $header): self
    {
        $this->header = $header;
        return $this;
    }

    /**
     * 
     * @param array<string> $data 
     * @return void 
     * @throws CsvStreamWriteException 
     */
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

        $this->storage->append($this->outputFilename, $this->toCsv($data));
    }

    /**
     * @param array<string> $data 
     * @return string 
     */
    private function toCsv(array $data): string {
        return implode(self::SEPARATOR, $data) . self::ENDLINE;
    }
}
