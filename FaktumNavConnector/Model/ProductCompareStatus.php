<?php

namespace Immerce\FaktumNavConnector\Model;

use Immerce\FaktumNavConnector\Importer\ProductImporter;
use Magento\Catalog\Model\Category;
use Magento\Catalog\Model\Product;
use Magento\CatalogInventory\Api\StockRegistryInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Store\Api\WebsiteRepositoryInterface;
use Magento\Catalog\Model\CategoryFactory;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;

class ProductCompareStatus
{
    private Product $product;
    private array $productXmlItem;
    private StockRegistryInterface $stockRegistry;
    private WebsiteRepositoryInterface $websiteRepository;
    private CategoryFactory $categoryFactory;

    public function __construct(
        Product $product,
        array $productXmlItem = [],
        StockRegistryInterface $stockRegistry,
        WebsiteRepositoryInterface $websiteRepository,
        CategoryFactory $categoryFactory
    )
    {
        $this->product = $product;
        $this->productXmlItem = $productXmlItem;
        $this->stockRegistry = $stockRegistry;
        $this->websiteRepository = $websiteRepository;
        $this->categoryFactory = $categoryFactory;
    }
    public function setProduct(Product $product)
    {
        return $this->product = $product;
    }
    public function setXmlItems(array $xml)
    {
        return $this->productXmlItem = $xml;
    }
    public function hasProduct()
    {
        if (($this->product instanceof Product) && ($this->product->getId() > 0)) {
            return true;
        }
        return false;
    }

    public function hasSameData(string $attribute)
    {
        $status = false;
        switch ($attribute) {
            case "weight":
                $status = self::duplicateValue('weight');
                break;
            case "product_type":
                $status = self::duplicateTypeId();
                break;
            case "name":
                $status = self::duplicateValue('name');
                break;
            case "description":
                $status = self::duplicateValue('description');
                break;
            case "short_description":
                $status = self::duplicateValue('short_description');
                break;
            case "stock":
                $status = self::duplicateStockItem();
                break;
            case "categories":
                $status = self::duplicateCategories();
                break;
            case "linked_products":
                if(isset($this->productXmlItem['linked_products']['crosssell_skus'])){
                    $status = self::duplicateCrossSell();
                }

                if(isset($this->productXmlItem['linked_products']['upsell_skus'])){
                    $status = self::duplicateUpsell();
                }

                if(isset($this->productXmlItem['linked_products']['related_skus'])){
                    $status = self::duplicateRelated();
                }
                break;
            case "pricing":
                $status = self::comparePrices();
                break;
        }

        return $status;
    }


    /**
     * If return True it mean the value is same on Database, don't give a process
     * If false will update the dataset again.
     * @param string $attributeName
     * @return bool
     */
    private function duplicateValue(string  $attributeName)
    {
        if(!isset($this->productXmlItem[$attributeName])){
            return true;
        }

        $xmlValue = $this->productXmlItem[$attributeName] ?? null;
        $productValue = $this->product->getData($attributeName) ?? null;

        return self::compareValues($productValue , $xmlValue);

    }
    private function compareValues($productValue , $xmlValue)
    {
        return ( $productValue === $xmlValue) ? true : false;
    }
    private function duplicateTypeId()
    {
        if(!isset($this->productXmlItem['product_type'])){
            return true;
        }

        $xmlValue = $this->productXmlItem['product_type'] ?? null;
        $productValue = $this->product->getTypeId() ?? null;

        return self::compareValues($productValue , $xmlValue);
    }
    private function duplicateStockItem()
    {
        if(!isset($this->productXmlItem['stock'])){
            return true;
        }

        $xmlValue = (float) $this->productXmlItem['stock']['qty'] ?? 0;
        $websiteId = (int) $this->websiteRepository->get('base')->getId();

        $stockItem = $this->stockRegistry->getStockItem($this->product->getId(), $websiteId);
        $productValue = (float) $stockItem->getQty() ?? null;

        return self::compareValues($productValue , $xmlValue);
    }

    private function duplicateCategories(): bool
    {
        if(!isset($this->productXmlItem['categories'])){
            return true;
        }

        $assignIds = $this->getCategoryIdByEntity($this->productXmlItem['categories']);
        $categoryIds = $this->product->getCategoryIds() ?? [];

        if(is_array($assignIds)){
            foreach ($assignIds as $categoryId){

                if(!in_array($categoryId, $categoryIds)){
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * Retrive category ids by attribute 'nav_category_id'
     * @param array $categories
     * @return array
     */
    private function getCategoryIdByEntity(array $categories) : array
    {
        $assignIds = [];
        if(array_key_exists('category', $categories)){
            //resovle in case have 1 value
            $categories['category'] = (is_array($categories['category'])) ? $categories['category'] : [$categories['category']];

            foreach (array_filter($categories['category']) as $key => $attribute) {
                /** @var Category $category */
                $category = $this->categoryFactory->create()->loadByAttribute('nav_category_id', $attribute);
                if($category instanceof Category){

                    $categoryId = $category->getId();
                    $categoryLevel = $category->getLevel();
                    /**
                     * Resolved bug url rewrite too long ... ( > 255 varchar)
                     */
                    if($categoryLevel < ProductImporter::CATEGORY_LEVEL_LIMIT){
                        $assignIds[] = $categoryId;
                    }
                }
            }
        }
        return $assignIds;
    }
    protected function duplicateCrossSell()
    {
        if(!isset($this->productXmlItem['linked_products']['crosssell_skus'])){
            return true;
        }

        $xmlSkuValues = explode(",", $this->productXmlItem['linked_products']['crosssell_skus']);
        $crossSellIds =  $this->product->getCrossSellProductIds();
        $crossSellSku = $this->getProductSkuByIds($crossSellIds);

        return self::compareTwoArray($crossSellSku, $xmlSkuValues);
    }
    protected function duplicateUpsell()
    {
        if(!isset($this->productXmlItem['linked_products']['upsell_skus'])){
            return true;
        }

        $xmlSkuValues = explode(",", $this->productXmlItem['linked_products']['upsell_skus']);
        $upSellIds =  $this->product->getUpSellProductIds();
        $upSellSku = $this->getProductSkuByIds($upSellIds);

        return self::compareTwoArray($upSellSku, $xmlSkuValues);
    }
    protected function duplicateRelated()
    {
        if(!isset($this->productXmlItem['linked_products']['related_skus'])){
            return true;
        }

        $xmlSkuValues = explode(",", $this->productXmlItem['linked_products']['related_skus']);
        $relatedIds =  $this->product->getRelatedProductIds();
        $relatedSku = $this->getProductSkuByIds($relatedIds);



        return self::compareTwoArray($relatedSku, $xmlSkuValues);
    }
    private function getProductSkuByIds(array $ids)
    {
        $result = [];
        $ObjectManager = ObjectManager::getInstance();
        $collection = $ObjectManager->create(CollectionFactory::class)->create()
            ->addFieldToFilter('entity_id', ['in' => $ids])
            ->addAttributeToSelect('*');

        foreach ($collection as $product) {
            $result[] = $product->getSku();
        }

        return $result;
    }

    private function compareTwoArray(array $start, array $end): bool
    {
        /** chek matching between two array */
        $diff = array_diff($start, $end);
        if(count($end) > count($start)){
            $diff = array_diff($end, $start);
        }
        return (count($diff) === 0) ? true : false;
    }

    public function comparePrices()
    {
        if(!isset($this->productXmlItem['pricing'])){
            return true;
        }

        $status = false;
        foreach ($this->productXmlItem['pricing'] as $key => $price){

            switch ($key) {
                case "price":
                    $status = self::comparePrice($this->productXmlItem['pricing']);
                    break;
                case "special_price":
                    $status = self::compareSpecialPrice($this->productXmlItem['pricing']);
                    break;
                case "special_price_from_date":
                    $status = self::compareSpecialFromDate($this->productXmlItem['pricing']);
                    break;
                case "special_price_to_date":
                    $status = self::compareSpecialToDate($this->productXmlItem['pricing']);
                    break;
                case "tax_class_name":
                    $status = self::compareClassTax($this->productXmlItem['pricing']);
                    break;
                default:
                    $status = false;
                    break;
            }

            //return false if have some attribute has false.
            if($status === false)
            {
                return $status;
            }
        }

        return $status;
    }
    public function comparePrice(array $prices): bool
    {
        if(!isset($prices['price'])){
            return true;
        }
        return ((float) $prices['price'] === (float) $this->product->getPrice()) ? true : false;
    }
    public function compareSpecialPrice(array $prices): bool
    {
        if(!isset($prices['special_price'])){
            return true;
        }
        return ((float) $prices['special_price'] === (float) $this->product->getSpecialPrice()) ? true : false;
    }
    public function compareSpecialFromDate(array $prices): bool
    {
        if(!isset($prices['special_price_from_date'])){
            return true;
        }
        return ($prices['special_price_from_date'] === $this->product->getSpecialFromDate()) ? true : false;
    }
    public function compareSpecialToDate(array $prices): bool
    {
        if(!isset($prices['special_price_to_date'])){
            return true;
        }
        return ($prices['special_price_to_date'] === $this->product->getSpecialToDate()) ? true : false;
    }
    public function compareClassTax(array $prices): bool
    {
        if(!isset($prices['tax_class_name'])){
            return true;
        }

        $objectManager = ObjectManager::getInstance();
        $taxClassId = $this->product->getTaxClassId() ?? 0;
        $taxClass = $objectManager->create('Magento\Tax\Model\ClassModel')->load($taxClassId);

        return ((string) $prices['tax_class_name'] === (string) $taxClass->getClassName()) ? true : false;
    }
}
