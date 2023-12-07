<?php
declare(strict_types=1);

namespace Immerce\VerpackenProductsContent\Model;

use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\Product\Visibility;
use Magento\Catalog\Model\ProductFactory;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\App\Area;
use Magento\Framework\App\State;
use Magento\Catalog\Api\Data\ProductInterface;

class ProductUpdate
{
    private ProductFactory $productFactory;
    private CollectionFactory $collectionFactory;
    private StoreManagerInterface $storeManager;
    private State $state;

    public function __construct(
        ProductFactory $productFactory,
        CollectionFactory $collectionFactory,
        StoreManagerInterface $storeManager,
        State $state
    )
    {

        $this->productFactory = $productFactory;
        $this->collectionFactory = $collectionFactory;
        $this->storeManager = $storeManager;
        $this->state = $state;
    }

    public function execute()
    {

        $this->state->setAreaCode(Area::AREA_ADMINHTML);
        $collection = $this->collectionFactory->create();
        $productIds = $collection->getAllIds();

        $i = 1;
        $count = $collection->count();

        exec(\Safe\sprintf('\r\n\t'));
        foreach ($productIds as $id){
            $this->updateProducting((int) $id);

            echo $this->progressBar($i, $count) ;
            $i++;
        }

    }
    private function updateProducting(int $productId)
    {

        $storeId = 0;
        $store = $this->storeManager->getStore($storeId);
        $this->storeManager->setCurrentStore($store->getCode());

        $product = $this->productFactory->create()->load($productId);

        /** Get product attributes */
        $productDecription = $product->getDescription();
        $shortDecription = $product->getShortDescription();


        /** check the values exit on default product */
        $defaultProduct = $this->getProductDefault($productId);
        if ($defaultProduct instanceof Product) {
            /** replace attributes to default values */
            $productDecription = $defaultProduct->getDescription();
            $shortDecription = $defaultProduct->getShortDescription();

        }

        /** set product description */
        if($productDecription){
            if(!$this->modifiedReady($productDecription)){
                $product->setDescription( $this->template($productDecription) );
            }
        }

        /** set product short description */
        if($shortDecription){
            /** Check content update ready??  */
            if(!$this->modifiedReady($shortDecription)){
                $template = $this->template($shortDecription);
                $product->setShortDescription($template);

            }
        }


        $product->save();
        /**
         * After done. run sql remove a recode.
         *
        DELETE FROM `catalog_product_entity_text` WHERE `store_id` = 1 AND `attribute_id` = 75;
        DELETE FROM `catalog_product_entity_text` WHERE `store_id` = 1 AND `attribute_id` = 76;

        DELETE FROM `catalog_product_entity_datetime` WHERE `store_id` = 1 AND `attribute_id` = 75;
        DELETE FROM `catalog_product_entity_datetime` WHERE `store_id` = 1 AND `attribute_id` = 76;

        DELETE FROM `catalog_product_entity_decimal` WHERE `store_id` = 1 AND `attribute_id` = 75;
        DELETE FROM `catalog_product_entity_decimal` WHERE `store_id` = 1 AND `attribute_id` = 76;

        DELETE FROM `catalog_product_entity_int` WHERE `store_id` = 1 AND `attribute_id` = 75;
        DELETE FROM `catalog_product_entity_int` WHERE `store_id` = 1 AND `attribute_id` = 76;

        DELETE FROM `catalog_product_entity_varchar` WHERE `store_id` = 1 AND `attribute_id` = 75;
        DELETE FROM `catalog_product_entity_varchar` WHERE `store_id` = 1 AND `attribute_id` = 76;
         *
         */
    }
    public function getProductDefault($productId)
    {
        $storeId = 1;
        try {
            $product = $this->productFactory->create()
                ->setStoreId($storeId)
                ->load($productId);
        } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
            return null;
        }

        if (!$product->getId()) {
            return null;
        }

        return $product;
    }

    public function modifiedReady(string $description): bool
    {
        if (strpos($description, '<style>#html-body [data-pb-style=') !== false){
            return true;
        }
        return  false;
    }

    private function template($decription)
    {
        $pbStyle = $this->dataPbStyleCode();

        return <<<HTML
        <style>#html-body [data-pb-style=$pbStyle]{justify-content:flex-start;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll}</style><div class="product-short-description-seciton" data-content-type="row" data-appearance="full-bleed" data-enable-parallax="0" data-parallax-speed="0.5" data-background-images="{}" data-background-type="image" data-video-loop="true" data-video-play-only-visible="true" data-video-lazy-load="true" data-video-fallback-src="" data-element="main" data-pb-style="$pbStyle"><div data-content-type="text" data-appearance="default" data-element="main">$decription</div></div>
        HTML;

    }

    private function dataPbStyleCode(): string
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        return substr(str_shuffle($characters), 0, 7);
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
        return sprintf("Update product content processing : %s%%[%s>%s]%s\r", $perc, str_repeat('=', $bar), str_repeat(' ', $width-$bar), $info);
    }
}
