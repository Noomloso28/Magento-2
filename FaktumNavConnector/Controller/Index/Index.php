<?php

declare(strict_types=1);

namespace Immerce\FaktumNavConnector\Controller\Index;

use Magento\Catalog\Api\ProductRepositoryInterfaceFactory;
use Magento\Catalog\Model\Product;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Immerce\FaktumNavConnector\Cron\ProductImporterCron;
use Magento\Catalog\Model\ProductFactory;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Catalog\Helper\Image;
use Immerce\FaktumNavConnector\Model\DisableAllProducts;

class Index extends Action
{

    /**
     * @var ProductImporterCron
     */
    private ProductImporterCron $productImporterCron;
    private ProductFactory $productFactory;
    private CollectionFactory $collectionFactory;
    private ProductRepositoryInterfaceFactory $productRepositoryFactory;
    private DisableAllProducts $disableAllProducts;

    /**
     * @param Context $context
     */

    public function __construct(
        Context $context,
        ProductImporterCron $productImporterCron,
        ProductFactory $productFactory,
        CollectionFactory $collectionFactory,
        ProductRepositoryInterfaceFactory $productRepositoryFactory,
        Image $imageHelper,
        DisableAllProducts $disableAllProducts
    ) {

        $this->productImporterCron = $productImporterCron;
        $this->productFactory = $productFactory;
        $this->collectionFactory = $collectionFactory;
        $this->productRepositoryFactory = $productRepositoryFactory;
        $this->imageHelper = $imageHelper;
        $this->disableAllProducts = $disableAllProducts;

        parent::__construct($context);
    }

    public function execute()
    {
        // TODO: Implement execute() method.

        $this->productImporter();
//        $this->getImageBySku('01165');
//        self::disableProducts();
    }

    public function disableProducts()
    {
        $this->disableAllProducts->execute();
    }

    public function getImageBySku(string $sku)
    {
//        $_product = $this->productFactory->create()-> loadByAttribute('sku', $sku);

//
//        $product = $this->productRepositoryFactory->create()
//            ->getById($_product->getId());
//        var_dump($product->getData('image'));

//        // Get the base image URL
//        $imageUrl = $this->imageHelper->init($_product, 'product_page_image_large')->getUrl();
//
//        var_dump($imageUrl);



//
//        $productImages = $_product->getMediaGalleryImages();
////        var_dump($_product->getData('media_gallery_images'));
//        var_dump($_product->getData('media_gallery_images')->toArray());
//
//         foreach( $_product->getData('media_gallery_images') as $productimage)
//            {
//               var_dump($productimage);
//            }


        // Load the product by SKU
//        $product = $this->getProductBySku($sku);
//        if ($product) {
//
//
//             $_product = $this->productRepositoryFactory->create()
//                ->get($sku);
//
//            $mediaGalleryEntries = $_product->getMediaGalleryEntries();
//            foreach ($mediaGalleryEntries as $entry) {
//                $imageUrl = $entry->getFile();
//
//                var_dump($imageUrl."\t");
//                echo '<br>';
//
//            }


//            $productimages = $product->getMediaGalleryImages();
//
//            var_dump($product->getData('name'));
//            foreach($productimages as $productimage)
//            {
//               var_dump($productimage);
//            }
//
//            $mediaGalleryEntries = $product->getMediaGalleryEntries();
//
//            var_dump($mediaGalleryEntries);
//
//            if (!empty($mediaGalleryEntries)) {
//                // Get the first media gallery entry as an example
//                $mediaGalleryEntry = $mediaGalleryEntries[0];
//
//                $imageUrl = $mediaGalleryEntry->getFile();
//                echo 'Product Image URL: ' . $imageUrl;
//            }
//
//            var_dump($mediaGalleryEntries);

            // Get product images
//            $mediaGalleryEntries = $product->getMediaGalleryEntries();
//            $gallery = $product->getMediaGalleryImages();
//
//            var_dump(count($gallery));
//            if (count($gallery) > 0) {
//                foreach ($gallery as $image) {
//                    var_dump(
//                        $image->getValueId()
//                    );
//                }
//            }


//            foreach ($mediaGalleryEntries as $entry) {
//                $imagePath = $entry->getFile();
//                $imageUrl = $product->getMediaConfig()->getMediaUrl($imagePath);
//                // Now, you can use $imagePath and $imageUrl as needed
//                var_dump($imageUrl);
//            }
//        }

    }

    protected function getProductBySku(string $sku)
    {
        $collection = $this->collectionFactory->create();
        $collection->addAttributeToSelect('*');
        $collection->addFieldToFilter(Product::SKU, $sku);
        $collection->setFlag('has_stock_status_filter', false); //get all status.

        if($collection->getSize() > 0){
            return $collection->getFirstItem();
        }

        return false;
    }

    public function productImporter()
    {
        $productFileUrl = '';

        return $this->productImporterCron->execute();

    }
}
