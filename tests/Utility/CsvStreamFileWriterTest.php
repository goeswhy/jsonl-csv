<?php
namespace App\Tests\Utility;

use Mockery;
use PHPUnit\Framework\TestCase;
use Mockery\LegacyMockInterface;
use App\Utility\CsvStreamFileWriter;
use App\Utility\Contract\IFileStorage;
use App\Utility\Exception\CsvStreamWriteException;

class CsvStreamFileWriterTest extends TestCase {
    private CsvStreamFileWriter $instance;
    private $fileStorageMock;

    protected function setUp(): void
    {
        /**
         * @var IFileStorage|LegacyMockInterface
         */
        $this->fileStorageMock = Mockery::mock(IFileStorage::class);
        $this->fileStorageMock->shouldReceive('touch')->andReturn();

        $this->instance = new CsvStreamFileWriter($this->fileStorageMock, 'file');
    }

    protected function tearDown(): void
    {
        Mockery::close();
    }

    /**
     * @testdox It throws CsvStreamWriteException::class on empty header
     */
    public function testEmptyHeader()
    {
        $this->expectException(CsvStreamWriteException::class);
        $this->expectExceptionMessage('Write failed, empty header');
        $data = ['data 2 col 1', 'data 2 col 2'];
        $this->instance->write($data);
    }

    /**
     * @testdox It throws CsvStreamWriteException::class on invalid header column length
     */
    public function testInvalidHeaderColumnLength()
    {
        $this->expectException(CsvStreamWriteException::class);
        $this->expectExceptionMessage('Write failed, invalid header column length');
        $header = ['col_1'];
        $data = ['data 2 col 1', 'data 2 col 2'];
        $this->instance->setHeader($header)->write($data);
    }

    /**
     * @testdox It should write file from array
     * @doesNotPerformAssertions
     */
    public function testWrite() {
        $header = ['col_1', 'col_2'];
        $data = ['data 2 col 1', 'data 2 col 2'];
        $this->fileStorageMock
            ->shouldReceive('append')
            ->with('file', "data 2 col 1;data 2 col 2\n");
        $this->instance->setHeader($header)->write($data);
    }
}