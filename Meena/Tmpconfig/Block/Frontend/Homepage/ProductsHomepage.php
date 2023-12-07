<?php
namespace Meena\Tmpconfig\Block\Frontend\Homepage;

use Magento\Framework\View\Element\Template;
use Magento\Cms\Model\Template\FilterProvider;

use Meena\Tmpconfig\Model\ResourceModel\Template\CollectionFactory;
//use Magento\Widget\Block\BlockInterface;

//use Magento\Framework\App\ObjectManager;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;
use Magento\Catalog\Model\ProductRepository;
use Magento\Framework\Pricing\PriceCurrencyInterface;

use Magento\Catalog\Api\ProductRepositoryInterfaceFactory;

//implements BlockInterface
class ProductsHomepage extends Template
{

    /**@var_CollectionFactory */
    protected $collection;

    /**
     * @var_Magento\Cms\Model\Template\FilterProvider
     */
    protected $_filterProvider;

    /**
     * @var_Magento\Catalog\Model\ProductRepository
     */
    protected $_productRepository;

    /*
     * @var Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $_productCollectionFactory;

    /** @var_ImageHelper */
    private $productRepositoryFactory;

    /*
     * @var Magento\Framework\Pricing\PriceCurrencyInterface
     */
    private $PriceCurrencyInterface;


    public function __construct(
        Template\Context $context,
        array $data = array(),
        CollectionFactory $CollectionFactory,
        FilterProvider $filterProvider,
        ProductCollectionFactory $productCollectionFactory,
        ProductRepository $productRepository,
        ProductRepositoryInterfaceFactory $productRepositoryFactory,
        PriceCurrencyInterface $PriceCurrencyInterface
    )
    {

        $this->_filterProvider = $filterProvider;
        parent::__construct($context, $data);

        $this->collection = $CollectionFactory->create();
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->_productRepository = $productRepository;
        $this->productRepositoryFactory = $productRepositoryFactory;
        $this->PriceCurrencyInterface = $PriceCurrencyInterface;
    }


    /**
     * Prepare HTML content
     *
     * @return string
     */
    private function getCmsFilterContent($value='')
    {
        $html = $this->_filterProvider->getPageFilter()->filter($value);
        return $html;
    }

    /*
     * Get All product details.
     */
    public function getProductDetails(){

        $collection = $this->getReportBestSeller( );

        /*
        $collection = $this->_productCollectionFactory->create();
        $collection->addAttributeToSelect("color")->addAttributeToFilter(
            array(
                array('attribute'=> 'color','notnull' => true),
                array('attribute'=> 'color','neq' => ''),
                array('attribute'=> 'color','neq' => 'NO FIELD'),
            ),'','left'
        );
        $collection->setPageSize(8); // fetching only 10
        */

        $products = array();

        foreach ($collection as $product) {

            $all_products = $product->getData();
            $price = $this->getFormatedPrice( $this->_productRepository->getById( $all_products['product_id'] )->getPrice() );

            $all_products['name'] = $this->getProductById( $all_products['product_id'] );
            $all_products['price']  = $price;
            $all_products['productURL'] = $this->getProductUrl( $all_products['product_id']  );
            $all_products['image'] = $this->getImageUrl( $all_products['product_id'] );

            $products[] =  $all_products;
        }

        return $products;
    }

    private function getFormatedPrice( $amount = 0.00 )
    {

        return $this->PriceCurrencyInterface->convertAndFormat($amount);
    }

    private function getReportBestSeller( $type = 'year' ){

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $productCollection = $objectManager->create('Magento\Reports\Model\ResourceModel\Report\Collection\Factory');
        $collection = $productCollection->create('Magento\Sales\Model\ResourceModel\Report\Bestsellers\Collection');

        //$collection->setPeriod('month');
        $collection->setPeriod('year');
        //$collection->setPeriod('day');

        $collection->setPageSize(10);

        return $collection;

    }

    /*
     * Get Product URL in FrontEnd.
     */
    public function getProductUrl($productId){
        $product = $this->_productRepository->getById($productId);
        return $product->getProductUrl();

    }


    /*
     * Get product from ID
     */
    public function getProductById($id)
    {
        $_product =  $this->_productRepository->getById($id);

        return  $_product->getName();
    }

    /*
     * Get product From SKU
     */
    public function getProductBySku($sku)
    {
        $_product = $this->_productRepository->get($sku);

        return  $_product->getName();
    }

    /*
     * Get Product ID by SKU
     */

    private function getProductIDbySKU( $productId = null ){


    }

    /**
     * @param_\Magento\Catalog\Model\Product $product
     */
    public function getImageUrl( $getProductId )
    {
        $product = $this->productRepositoryFactory->create()
            ->getById( $getProductId );
        //$image = $product->getData('image');
        //$image =$product->getData('thumbnail');
        $image =$product->getData('small_image');

        return '/media/catalog/product/'.$image;
    }



    public function getProductList(){

        $objectManager =  \Magento\Framework\App\ObjectManager::getInstance();
        //$objectManager = new \ObjectManager::getInstance();
        $productCollection = $objectManager->get("Magento\Catalog\Model\Product")->getCollection();
        $productCollection->addAttributeToSelect("color")->addAttributeToFilter(
            array(
                array('attribute'=> 'color','notnull' => true),
                array('attribute'=> 'color','neq' => ''),
                array('attribute'=> 'color','neq' => 'NO FIELD'),
            ),'','left'
        );
        $productCollection->setPageSize(10);

        //show SQL command log
        //echo $productCollection->printLogQuery(true);

        $productIds = array();
        foreach ($productCollection as $product) {
            $productIds[] = array(
                                    'id' =>$product->getId(),

            );
        }

        return $productIds;
    }

    public function getText(){
        return 'Custom text';
    }


    public function displayValue(){

        $items = $this->collection->getItems();

        return  $this->getDataDB( $items );
    }



    private function  getDataDB( $allArray = array() ){

        $result = array();

        foreach ($allArray as $values) {

            $array = $values->getData();

            $result[$array['name']] = $this->getCmsFilterContent( $array['value'] );

        }

        return  json_decode(json_encode($result));
    }

}
