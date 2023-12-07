<?php

namespace DesignPattern\MagentoFactory\Controller\Index;

use DesignPattern\MagentoFactory\Block\ProductCollectionFactory;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\View\Result\PageFactory;
use DesignPattern\MagentoFactory\ProductInterface\ProductCollectionInterfaceFactory;
use DesignPattern\MagentoFactory\Services\ProductCollectServiceFactory;

class Index extends Action implements HttpGetActionInterface
{
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;
    private ProductCollectionFactory $productCollectionFactory;
    private ProductCollectionInterfaceFactory $productCollectionInterfaceFactory;
    private ProductCollectServiceFactory $collectServiceFactory;

    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        ProductCollectionFactory $productCollectionFactory,
        ProductCollectionInterfaceFactory $productCollectionInterfaceFactory,
        ProductCollectServiceFactory $collectServiceFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->productCollectionInterfaceFactory = $productCollectionInterfaceFactory;
        $this->collectServiceFactory = $collectServiceFactory;

        parent::__construct($context);
    }

    /**
     * Execute action based on request and return result
     *
     * @return ResultInterface|ResponseInterface
     * @throws NotFoundException
     */
    public function execute()
    {
        // TODO: Implement execute method.
        $productCollection = $this->productCollectionFactory->create([
                'data' => [
                    'name' => 'Arthit Chaingam 37'
                ]
            ]
        );

        var_dump(
            $productCollection->getName()
        );

        //Interface Factory.
        $productCollectionInterfaceFactory = $this->productCollectionInterfaceFactory->create();
        $productCollectionInterfaceFactory->setName('Product name by Interface');

        var_dump($productCollectionInterfaceFactory->getName());

        $collectServiceFactory = $this->collectServiceFactory->create();
        $collectServiceFactory->setName('Set name by created method');
        var_dump( $collectServiceFactory->getName());


        return $this->resultPageFactory->create();
    }
}
