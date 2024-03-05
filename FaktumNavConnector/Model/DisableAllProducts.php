<?php

namespace Immerce\FaktumNavConnector\Model;

use Immerce\FaktumNavConnector\Importer\ImportDataLoader\ImportDataLoaderInterface;
use Magento\Catalog\Model\Product\Attribute\Source\Status;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\App\State as AppState;
use Magento\Catalog\Model\ProductFactory;
use Magento\Store\Api\StoreRepositoryInterface;
use Magento\Store\Model\StoreManagerInterface;


class DisableAllProducts
{
    private CollectionFactory $collectionFactory;
    private AppState $appState;
    private ImportDataLoaderInterface $importDataLoader;
    public $xmlData = array();
    private ProductFactory $productFactory;
    /**
     * @var StoreManagerInterface
     */
    private StoreManagerInterface $storeManager;

    public function __construct(
        CollectionFactory $collectionFactory,
        AppState $appState,
        ImportDataLoaderInterface $importDataLoader,
        ProductFactory $productFactory,
        StoreManagerInterface $storeManager,
        StoreRepositoryInterface $storeRepository
    )
    {
        $this->collectionFactory = $collectionFactory;
        $this->appState = $appState;
        $this->importDataLoader = $importDataLoader;
        $this->productFactory = $productFactory;
        $this->storeManager = $storeManager;
        $this->storeRepository = $storeRepository;
    }

    public function execute()
    {
        exec(\Safe\sprintf("Updating all products to Disable status ...\r"));

        self::disableProducts();

//        self::setStoreTodefault();
    }

    private function getDisableProductIds()
    {
        /**
         * 1). Get all product ids
         * 2). Get product ids from xml import file
         * 3). Compare and get different ids
         * 4). Update the different ids to disable.
         */

        $productIds = self::getAllProductIds();
        $xmlProductIds = self::getXmlProductIds();
        $disableProductIds = [];


        if ((count($productIds) > 0) && (count($xmlProductIds) > 0)){
            $disableProductIds = self::compareTwoArray($productIds, $xmlProductIds);
        }

        return $disableProductIds;
    }
    public function getAllProductIds()
    {
        return $this->collectionFactory->create()->getAllIds();
    }
    public function loadXml(array $data)
    {
        $this->xmlData = $data;

        return $this;
    }
    public function getXmlProductSku(): array
    {
        if(count($this->xmlData) > 0){

            return array_map(function ($product){
                return $product['sku'];
            }, $this->xmlData);
        }
        return [];
    }

    public function getProductsBySku(array $skus)
    {
        $productCollection = $this->collectionFactory->create();
        $productCollection->addAttributeToSelect('*');
        if(is_array($skus) && count($skus) > 0){
            $productCollection->addAttributeToFilter('sku',['in' => $skus]);
        }

        return $productCollection;
    }
    public function getXmlProductIds()
    {
        $skus = self::getXmlProductSku();
        $productIds = [];

        if(count($skus) > 0){
            $productCollection = $this->getProductsBySku($skus);

            foreach ($productCollection as $product){
                $productIds[] = $product->getId();
            }
        }

        return $productIds;
    }

    private function compareTwoArray(array $start, array $end): array
    {
        /** chek matching between two array */
        $diff = array_merge(array_diff($start,$end),array_diff($end,$start));

        return (count($diff) !== 0) ? $diff : [];
    }

    public function disableProducts()
    {
        $disableIds = self::getDisableProductIds();

        if(count($disableIds) > 0){
            $productCollection = $this->collectionFactory->create();
            $productCollection->addAttributeToSelect('*');
            $productCollection->addAttributeToFilter('entity_id', ['id' => $disableIds]);

            $i = 1;
            $count = $productCollection->count();

            exec(\Safe\sprintf('\r\n\t'));
            foreach ($productCollection as $product) {
                if ($product->getStatus() != Status::STATUS_DISABLED) {
                    $product->setStatus(Status::STATUS_DISABLED);
                    $product->save();
                }

                echo $this->progressBar($i, $count) ;
                $i++;
            }
        }
    }

    public function setStoreTodefault()
    {
        $productCollection = $this->collectionFactory->create();
        $productCollection->addAttributeToSelect('*');

        $storeId = (int) $this->storeRepository->get('default')->getId();
        $productCollection->addStoreFilter($storeId);
        $i = 1;
        $count = $productCollection->count();
        if($count > 0){
            $attributesToReset = [
                'status',          // Enable Product
                'name',            // Name
                'tax_class_id',    // Tax Class
                'visibility',      // Visibility
                'description',     // Description
                'short_description' // Short Description
            ];

            exec(\Safe\sprintf('\r\n\t'));
            foreach ($productCollection as $product) {

                foreach ($attributesToReset as $attributeCode) {
                    $product->setData($attributeCode, null);
                }

                $product->save();

                echo $this->progressBar($i, $count) ;
                $i++;
            }
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
        return sprintf("All Products disable processing : %s%%[%s>%s]%s\r", $perc, str_repeat('=', $bar), str_repeat(' ', $width-$bar), $info);
    }
}
