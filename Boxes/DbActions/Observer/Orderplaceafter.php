<?php
namespace Boxes\DbActions\Observer;

use Magento\Framework\Event\ObserverInterface;
use Psr\Log\LoggerInterface;

class Orderplaceafter implements ObserverInterface
{
    protected $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        try {
            $order = $observer->getEvent()->getOrder();
            $this->logger->info( "test:". json_encode( $order ) );

        } catch (\Exception $e) {
            $this->logger->info($e->getMessage());
        }
    }
}
