<?php

namespace Amigos\WinterPromotion2022\Block;

use Magento\Catalog\Helper\Image;
use Magento\Catalog\Model\CategoryFactory;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\View\Element\Template;
use Magento\Store\Model\ScopeInterface;

class CategoryPromotions extends Template
{
    /**
     * @var CategoryFactory
     */
    private CategoryFactory $categoryFactory;
    /**
     * @var ScopeConfigInterface
     */
    private ScopeConfigInterface $scopeConfig;
    /**
     * @var CollectionFactory
     */
    private CollectionFactory $collectionFactory;
    /**
     * @var Image
     */
    private Image $image;

    public function __construct(
        Template\Context $context,
        CategoryFactory $categoryFactory,
        ScopeConfigInterface $scopeConfig,
        CollectionFactory $collectionFactory,
        Image $image,
        array $data = []
    ) {
        $this->categoryFactory = $categoryFactory;
        $this->scopeConfig = $scopeConfig;
        $this->collectionFactory = $collectionFactory;
        $this->image = $image;
        parent::__construct($context, $data);
    }

    /**
     * retrieve category data by category id
     * @param int $id
     * @return object
     */
    public function getCategoryById(int $id): object
    {
        $category = $this->categoryFactory->create();
        $category->load($id);

        return $category;
    }

    /**
     * Get system data value from database.
     * @param string $value
     * @param int|null $storeId
     * @return string
     */
    public function getDefaultValue(string $value, ?int $storeId = 0): ?string
    {
        return $this->scopeConfig->getValue(
            $value,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }


    /**
     * Get product data by array of ids.
     * @param array $ids
     * @return object
     */
    public function getProductsByIds(array $ids): object
    {
        $collection = $this->collectionFactory->create();
        $collection
            ->addAttributeToSelect('*')
            ->addIdFilter($ids);

        return $collection;
    }

    /**
     * @param object $product
     * @return string
     */
    public function getProductImage(object $product, int $width, ?int $height): string
    {
        /** @var Product $product */
//        $imageUrl = $this->image->init($product,
//            'product_thumbnail_image')->setImageFile($product->getFile())->getUrl();
        $imageUrl = $this->image;
        $imageUrl->init($product, 'product_thumbnail_image');
        $imageUrl->setImageFile($product->getFile());
        if($width){
            $imageUrl->resize($width, $height);
        }

        return $imageUrl->getUrl();
    }
}
