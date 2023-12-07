<?php
namespace Boxes\HelloWorld\Model\Products;
use Magento\Framework\Model\AbstractModel;

class ProductReviews extends AbstractModel{

    private \Magento\Store\Model\StoreManagerInterface $_storeManager;
    private \Magento\Catalog\Model\ProductFactory $_productFactory;
    private \Magento\Review\Model\RatingFactory $_ratingFactory;
    private \Magento\Review\Model\ResourceModel\Review\CollectionFactory $_reviewFactory;

    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Review\Model\RatingFactory $ratingFactory,
        \Magento\Review\Model\ResourceModel\Review\CollectionFactory $reviewFactory
        ) {
        $this->_storeManager = $storeManager;
        $this->_productFactory = $productFactory;
        $this->_ratingFactory = $ratingFactory;
        $this->_reviewFactory = $reviewFactory;
    }


    public function getReviewCollection($productId){
        $collection = $this->_reviewFactory->create()
            ->addStatusFilter(
                \Magento\Review\Model\Review::STATUS_APPROVED
            )->addEntityFilter(
                'product',
                $productId
            )->setDateOrder();

        return $collection->getData();
    }

    public function getRatingCollection( $productId ){
       $ratingCollection = $this->_ratingFactory->create()
            ->getResourceCollection()
            ->addEntityFilter(
                'product',
                $productId
            )->setPositionOrder()->setStoreFilter(
                $this->_storeManager->getStore()->getId()
            )->addRatingPerStoreName(
                $this->_storeManager->getStore()->getId()
            )->load();

        return $ratingCollection->getData();
    }


}
