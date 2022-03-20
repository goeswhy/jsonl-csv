<?php
namespace App\Command;

use App\Mapper\Contract\IDataMapper;
use App\Utility\Contract\IChunkParser;
use App\Utility\Contract\IFormattedFileWriter;
use App\Utility\JsonLineChunkParser;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsCommand(
    name: 'app:order-download',
    description: 'Download and convert order to specified document (default csv)',
    hidden: false,
)]
class DownloadOrderCommand extends Command {
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
        private IChunkParser $parser,
        private IDataMapper $mapper,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $url = $_SERVER['SOURCE_URL'] ?? 'https://s3-ap-southeast-2.amazonaws.com/catch-code-challenge/challenge-1-in.jsonl';
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
                        $order = $this->mapper->map($parsed);
                        $this->storage->write([
                            $order->getId(),
                            $order->getOrderedAt(),
                            $order->getTotalPrice(),
                            $order->getAvgPrice(),
                            $order->getUniqueProductsNumber(),
                            $order->getOrderedProductsNumber(),
                            $order->getCustomer()->getState(),
                        ]);
                    }
                }

                if (!empty($data->getUnparsed())) {
                    $previousChunk = $data->getUnparsed();
                }
            }
        }

        // TODO Emit event on success
        return Command::SUCCESS;
    }
}