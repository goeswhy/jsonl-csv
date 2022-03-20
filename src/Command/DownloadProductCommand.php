<?php
namespace App\Command;

use App\Utility\Contract\IFormattedFileWriter;
use App\Utility\JsonLineChunkParser;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsCommand(
    name: 'app:product-download',
    description: 'Download and convert product to specified document (default csv)',
    hidden: false,
)]
class DownloadProductCommand extends Command {
    /**
     * 
     * @param HttpClientInterface $httpClient 
     * @param IFormattedFileWriter $storage 
     * @param JsonLineChunkParser $parser 
     * @return void 
     * @throws InvalidArgumentException 
     */
    public function __construct(
        private HttpClientInterface $httpClient,
        private IFormattedFileWriter $storage,
        private JsonLineChunkParser $parser,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $url = 'https://s3-ap-southeast-2.amazonaws.com/catch-code-challenge/challenge-1-in.jsonl';
        $response = $this->httpClient->request('GET', $url);

        $previousChunk = NULL;
        $this->storage->setHeader([
            'order_id',
            'order_datetime',
            'total_order_value',
            'average_unit_price',
            'distinct_unit_count',
            'total_units_count',
            'customer_state'
        ]);
        foreach($this->httpClient->stream($response) as $chunk) {
            $content = $chunk->getContent();
            if (!empty($content)) {
                $data = $this->parser->parse($content, $previousChunk);

                if (count($data->getParsed()) > 0) {
                    foreach($data->getParsed() as $parsed) {
                        $this->storage->write([
                            $parsed['order_id'],
                            'order_datetime',
                            'total_order_value',
                            'average_unit_price',
                            'distinct_unit_count',
                            'total_units_count',
                            'customer_state'
                        ]);
                    }
                }

                if (!empty($data->getUnparsed())) {
                    $previousChunk = $data->getUnparsed();
                }
            }
        }

        return Command::SUCCESS;
    }

    private function _run()
    {

        $url = 'https://s3-ap-southeast-2.amazonaws.com/catch-code-challenge/challenge-1-in.jsonl';
        $response = $this->httpClient->request('GET', $url);

        $fileHandler = fopen('./result', 'w');
        foreach($this->httpClient->stream($response) as $chunk) {
            $content = $chunk->getContent();
            if (!empty($content)) {
                $lines = array_filter(explode("\n", $chunk->getContent()), fn($line) => !empty($line));
                foreach($lines as $line) {
                    $line = json_decode($line);
                    $order = implode(';', [
                        $line->order_id,
                        $line->order_date,
                    ]);

                    fwrite($fileHandler, $order . PHP_EOL);
                }
            }
        }

        return Command::SUCCESS;
    }
}