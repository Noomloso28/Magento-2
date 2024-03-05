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

use Immerce\FaktumNavConnector\Importer\ImportProcessor\OrderImportProcessor;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Filesystem\DirectoryList;
use Magento\Framework\Phrase;

/**
 * Product importer cron class.
 *
 * @package Immerce\FaktumNavConnector\Cron
 * @since   1.0.0
 */
class OrderImporterCron
{
    protected OrderImportProcessor $importProcessor;
    private DirectoryList $directoryList;

    public function __construct(
        OrderImportProcessor $importProcessor,
        DirectoryList $directoryList
    ) {
        $this->importProcessor = $importProcessor;
        $this->directoryList = $directoryList;
    }

    public function execute()
    {
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


    }
}
