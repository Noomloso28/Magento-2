<?php
namespace Immerce\FaktumNavConnector\Console\Command;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Indexer\Model\IndexerFactory;
use Magento\Indexer\Model\Indexer\CollectionFactory;
class ProductReindex extends AbstractHelper
{
    private IndexerFactory $indexFactory;
    private CollectionFactory $indexCollection;

    public function __construct(
        Context $context,
        IndexerFactory $indexFactory,
        CollectionFactory $indexCollection
    )
    {
        parent::__construct($context);
        $this->indexCollection = $indexCollection;
        $this->indexFactory = $indexFactory;
    }

    public function updateIdexing()
    {
        $indexCollection = $this->indexCollection->create();
        $indexes = $indexCollection->getAllIds();
        $i = 1;
        $count = $indexCollection->count();

        exec(\Safe\sprintf('\r\n\t'));
        foreach ($indexes as $index){
            $indexFactory = $this->indexFactory->create()->load($index);
            $indexFactory->reindexAll($index);
            $indexFactory->reindexRow($index);

            echo $this->progressBar($i, $count) ;
            $i++;
        }
    }

    /**
     * Outputs the progress in the console.
     *
     * @param  int $done
     * @param  int $total
     * @param  string $info
     * @param  int $width
     * @return string
     */
    protected function progressBar(int $done, int $total, string $info = '', $width = 50)
    {
        $perc = round(($done * 100) / $total);
        $bar  = (int) round(($width * $perc) / 100);
        return sprintf("Reindex processing : %s%%[%s>%s]%s\r", $perc, str_repeat('=', $bar), str_repeat(' ', $width-$bar), $info);
    }

}
