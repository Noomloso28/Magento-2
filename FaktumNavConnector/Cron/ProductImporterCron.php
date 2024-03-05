<?php
/**
 * Product importer cron.
 *
 * @package Immerce\FaktumNavConnector\Cron
 * @version 1.0.0
 * @license GPL 2.0+
 */

declare(strict_types=1);

namespace Immerce\FaktumNavConnector\Cron;

use Immerce\FaktumNavConnector\Console\Command\ProductReindex;
use Immerce\FaktumNavConnector\Importer\ImportProcessor\ProductImportProcessor;
use Magento\Framework\App\Cache\Manager;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Filesystem\DirectoryList;
use Magento\Framework\Phrase;
use Immerce\FaktumNavConnector\Model\ProductImageGenerator;

/**
 * Product importer cron class.
 *
 * @package Immerce\FaktumNavConnector\Cron
 * @since   1.0.0
 */
class ProductImporterCron
{
    protected ProductImportProcessor $importProcessor;
    private DirectoryList $directoryList;
    /**
     * @var ProductImageGenerator
     */
    private ProductImageGenerator $productImageGenerator;
    private ProductReindex $productReindex;
    private Manager $cacheManager;

    public function __construct(
        ProductImportProcessor $importProcessor,
        DirectoryList $directoryList,
        ProductImageGenerator $productImageGenerator,
        ProductReindex $productReindex,
        Manager $cacheManager
    ) {
        $this->importProcessor = $importProcessor;
        $this->directoryList = $directoryList;
        $this->productImageGenerator = $productImageGenerator;
        $this->productReindex = $productReindex;
        $this->cacheManager = $cacheManager;
    }

    public function execute()
    {
        /** Check image before import product */
        echo sprintf("Import product images starting ... \r");
        $this->productImageGenerator->execute();



        /** product import starting .. */
        $file = $this->directoryList->getPath('var').$this->importProcessor->getProductPath();
        if (!file_exists($file)) {
            //extrack file type _*.xml
            $files = $this->importProcessor->getAllImportFiles($file);

            if(is_array($files) && count($files) > 0){
                foreach ($files as $key => $value){
                   $this->importProcessor->process( $value );
                }
            }else{
                throw new FileSystemException(new Phrase(sprintf('The import file not found: %s', $file)));
            }
        }else{
            $this->importProcessor->process( $file );
        }

        /** Reindex starting .. */
        $this->productReindex->updateIdexing();

        /** clear caches */
        $this->clearCaches();
    }
    public function clearCaches()
    {
        $this->cacheManager->clean($this->cacheManager->getAvailableTypes());
    }

}
