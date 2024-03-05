<?php
/**
 * Abstract import processor.
 *
 * @package Immerce\FaktumNavConnector\Importer\ImportProcessor
 * @version 1.0.0
 * @license GPL 2.0+
 */

declare(strict_types=1);

namespace Immerce\FaktumNavConnector\Importer\ImportProcessor;

use Immerce\FaktumNavConnector\Importer\AbstractImporter;
use Immerce\FaktumNavConnector\Importer\ImportDataLoader\ImportDataLoaderInterface;
use Immerce\FaktumNavConnector\Importer\ImportDataLoader\ImportXmlFileLoader;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Abstract import processor class.
 *
 * @package Immerce\FaktumNavConnector\Importer\ImportProcessor
 * @since   1.0.0
 */
abstract class AbstractImportProcessor implements ImportProcessorInterface
{
    const COMPLETED_FOLDER = 'completed';
    const FAILED_FOLDER = 'failed';
    protected ImportDataLoaderInterface $importDataLoader;

    protected AbstractImporter $importer;

    private Filesystem $filesystem;
    private ScopeConfigInterface $_scopeConfig;
    private StoreManagerInterface $_storeManager;

    public function __construct(
        ImportDataLoaderInterface $importDataLoader,
        AbstractImporter $importer,
        Filesystem $filesystem,
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager
    ) {
        $this->importDataLoader = $importDataLoader;
        $this->importer = $importer;
        $this->filesystem = $filesystem;
        $this->_scopeConfig = $scopeConfig;
        $this->_storeManager = $storeManager;
    }

    /**
     * @inheritDoc
     */
    public function process(string $impoortFile): bool
    {
        if ($this->importDataLoader instanceof ImportXmlFileLoader) {
            $this->importDataLoader->setRootNode($this->getEntity());
        }

        // Load data from file to import.
        $data = $this->importDataLoader->load($impoortFile);


        $status = $this->importer->import($data);

        //move uploaded file to each folder
        $this->moveUploadFile($impoortFile, $status);

        return $status;
    }

    /**
     * Retrieves attribute path from the current store.
     *
     * @return string
     */
    public function getProductPath(): string
    {
        $storeId = $this->getCurrentStore();
        $configValue = $this->configDataPath();

        return $this->_scopeConfig->getValue(
            $configValue,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }
    /**
     * @return int
     * @throws StoreManagerInterface
     */
    public function getCurrentStore(): int
    {
        return (int) $this->_storeManager->getStore()->getId();
    }


    private function moveUploadFile(string $importFile, bool $status)
    {
        $newFileName = basename($importFile,".xml")."-".date("d-m-Y")."-".time().'.xml';
        $entityPath = $this->getPathFolder($importFile);
        $completeDestination = $entityPath.self::COMPLETED_FOLDER;

        if($status !== true){
            $completeDestination = $entityPath.self::FAILED_FOLDER;
        }
        /** check folder destination exits */
        if($this->makeDir($completeDestination)){
                try {
                    $this->filesystem->getDirectoryWrite(DirectoryList::VAR_DIR)->copyFile(
                        $importFile,
                        $completeDestination.'/'.$newFileName
                    );

                    /** remove the xml file */
                    \Safe\unlink($importFile);

                }catch (\Exception $exception){
                    die($exception->getMessage());
                }
        }
    }

    /**
     * Check folder destination exits.
     * @param string $path
     * @return bool
     */
    private function makeDir(string $path): bool
    {
        if(!is_dir($path)){
            try {
                \Safe\mkdir($path);
            }catch (\Exception $exception){
                return false;
            }
        }
        return true;
    }

    /**
     * return full path.
     * @param string $importFile
     * @return string
     */
    protected function getPathFolder(string $importFile): string
    {
        $array = explode("/", $importFile);
        $filename = array_pop($array); //get filename
        return str_replace($filename,'',$importFile); //remove filename for the path
    }

    /**
     * @param string $importFile
     * @return array|false
     */
    public function getAllImportFiles(string $importFile)
    {
        $filenames = glob($importFile);
        if(is_array($filenames)){
            return $filenames;
        }
        return  false;
    }
}
