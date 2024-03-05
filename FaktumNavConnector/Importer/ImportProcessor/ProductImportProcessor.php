<?php
/**
 * Product import processor.
 *
 * @package Immerce\FaktumNavConnector\Importer\ImportProcessor
 * @version 1.0.0
 * @license GPL 2.0+
 */

declare(strict_types=1);

namespace Immerce\FaktumNavConnector\Importer\ImportProcessor;

use Immerce\FaktumNavConnector\Importer\ImportDataLoader\ImportDataLoaderInterface;
use Immerce\FaktumNavConnector\Importer\ProductImporter;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Filesystem;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Product import processor class.
 *
 * @package Immerce\FaktumNavConnector\Importer\ImportProcessor
 * @since   1.0.0
 */
class ProductImportProcessor extends AbstractImportProcessor
{
    public function __construct(
        ImportDataLoaderInterface $importDataLoader,
        ProductImporter $importer,
        Filesystem $filesystem,
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager
    ) {
        parent::__construct($importDataLoader, $importer, $filesystem, $scopeConfig, $storeManager);
    }

    /**
     * @inheritDoc
     */
    public function getEntity(): string
    {
        return 'product';
    }

    /**
     * @inheritDoc
     */
    public function configDataPath(): string
    {
        return 'navconnector/general/product_path';
    }
}
