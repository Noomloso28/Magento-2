<?php
namespace Boxes\HelloWorld\Block;

//use Magento\Catalog\Block\Product\Context;
use Magento\Framework\View\Element\Template\Context;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Sales\Model\ResourceModel\Report\Bestsellers\CollectionFactory as BestSellersCollectionFactory;
use Magento\Framework\View\Element\Template;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Catalog\Model\Product;

class ProductBestSeller extends Template{

    private BestSellersCollectionFactory $_bestSellersCollectionFactory;
    private CollectionFactory $_productCollectionFactory;
    private Product $product;



    public function __construct(
        Context $context,
        CollectionFactory $productCollectionFactory,
        StoreManagerInterface $storeManager,
        BestSellersCollectionFactory $bestSellersCollectionFactory,
        Product $product
    )
    {
        $this->_bestSellersCollectionFactory = $bestSellersCollectionFactory;
        $this->_storeManager = $storeManager;
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->product = $product;



        parent::__construct($context);
    }

    public function getProductCollection()
    {
        $productIds = [];
        $bestSellers = $this->_bestSellersCollectionFactory->create();
        //$bestSellers->setPageSize(6);
        $bestSellers->setPeriod('month')->addStoreFilter(1)->getSelect()->order('period DESC');

        foreach ($bestSellers as $product) {
            $productIds[] = $product->getProductId();
        }



        $collection = $this->_productCollectionFactory->create()->addIdFilter($productIds);
        $collection->addMinimalPrice()
            ->addFinalPrice()
            ->addTaxPercents()
            ->addAttributeToSelect('*')
            ->addStoreFilter($this->getStoreId())
            ->setPageSize(count($productIds));
        return $collection->getData();
    }

    public function getStoreId(){
        return $this->_storeManager->getStore()->getId();
    }




}
