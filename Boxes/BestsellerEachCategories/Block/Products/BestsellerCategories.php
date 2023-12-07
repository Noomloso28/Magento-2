<?php
namespace Boxes\BestsellerEachCategories\Block\Products;

use Magento\Framework\View\Element\Template;
use Magento\Sales\Model\ResourceModel\Report\Bestsellers\CollectionFactory;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Helper\Image;
//use Magento\Catalog\Block\Product\ImageBuilderFactory;
use Magento\Catalog\Model\ProductFactory as productModel;

class BestsellerCategories extends Template {

    /**
     * @var CollectionFactory
     */
    private CollectionFactory $collectFactory;
    /**
     * @var Image
     */
    private Image $image;

    /**
     * @var \Magento\Catalog\Block\Product\ImageBuilder
     */
    private  $imageBuilderFactory;
    /**
     * @var \Magento\Catalog\Helper\ImageFactory
     */
    private \Magento\Catalog\Helper\ImageFactory $helperFactory;

    private \Magento\Store\Model\StoreManagerInterface $storeManager;
    /**
     * @var productModel
     */
    private productModel $productModel;


    public function __construct(
        Template\Context $context,
        array $data = [],
        CollectionFactory $collectionFactory,
        Image $image,
        \Magento\Catalog\Block\Product\ImageBuilder $imageBuilderFactory,
        \Magento\Catalog\Helper\ImageFactory $helperFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        productModel $productModel
    )
    {
        $this->collectFactory = $collectionFactory;
        $this->image = $image;
        $this->imageBuilderFactory = $imageBuilderFactory;
        $this->helperFactory = $helperFactory;
        $this->storeManager = $storeManager;
        $this->productModel = $productModel;


        parent::__construct($context, $data);
    }


    public function ShowProductsBestSeller( array $category_ids ) {

        return  $this->getProductDetails( $category_ids );


    }

    public function getImageById(  $product ){

        $imageUrl = $this->image->init( $product , 'product_page_image_medium' )
           // ->setImageFile( $product->getSmallImage() )
            ->resize( 380 )
            ->getUrl();


        return $imageUrl;
    }

    public function displayProductImage( $product ){

        $image = $this->imageBuilderFactory->setProduct( $product )
            ->setImageId( 'product_page_image_medium' )
            ->setAttributes([])
            ->create();

        return $image->getImageUrl();
    }

    public function getImageURL($product , $attributes = [])
    {
        $image = $this->helperFactory->create()->init($product, 'product_base_image')
            ->constrainOnly(true)
            ->keepAspectRatio(true)
            ->keepTransparency(true)
            ->keepFrame(false)
            ->resize(200, 300);

        return $image->getUrl();
    }


    public function getMediaUrl( string $path)
    {

        return $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA).'catalog/product'.  $path;
    }


    /**
     * @param array $category_ids
     * @return \Magento\Sales\Model\ResourceModel\Report\Bestsellers\Collection
     */
    private function getProductDetails( array $category_ids ){

        $bestSellerProductByCategory = $this->collectFactory->create();
        $bestSellerProductByCategory->setModel('Magento\Catalog\Model\Product')
            ->setPeriod('month')
            ->join(['secondTable' => 'catalog_category_product'], 'sales_bestsellers_aggregated_monthly.product_id = secondTable.product_id', ['category_id' => 'secondTable.category_id'])
            ->addFieldToFilter('category_id', $category_ids);



        return $bestSellerProductByCategory;
    }


    public function getSimilarProduct( int $product_id ){

        /*$product = $this->productModel->load( $product_id );
        $getRelatedProducts = $product->getRelatedProducts();

        var_dump($getRelatedProducts);
        */
        $product = $this->productModel->create()->load( $product_id );
        $RelatedProducts =  $product->getRelatedProducts();

        var_dump( $RelatedProducts );

    }



}
