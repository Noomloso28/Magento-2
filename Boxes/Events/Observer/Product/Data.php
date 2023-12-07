<?php
namespace Boxes\Events\Observer\Product;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Psr\Log\LoggerInterface;

class Data implements ObserverInterface{

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;


    public function __construct(
        LoggerInterface $logger
    )
    {
        $this->logger = $logger;
    }

    /**
     * @param Observer $observer
     * @return void
     */
    public function execute( Observer $observer){

        $product = $observer->getProduct();
        $originalName = $product->getName();
        $price = $product->getPrice();

        $modifiedName = $originalName . ' - Catch by Events and Observers , Category = '. $product->getCategoryId();
        ///$modifiedName .= ',getQty = '.$product->getSpecialPrice();

        $product->setName($modifiedName);


        //set loger file for test
        //$this->logger->debug( 'Original name = '. $originalName . 'Modified name = '. $modifiedName );
    }
}
