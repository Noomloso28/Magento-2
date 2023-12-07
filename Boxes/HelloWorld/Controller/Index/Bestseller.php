<?php
namespace Boxes\HelloWorld\Controller\Index;


class Bestseller extends \Magento\Framework\App\Action\Action
{


    private \Magento\Framework\View\Result\PageFactory $resultPageFactory;


    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    )
    {
        $this->resultPageFactory = $resultPageFactory;
        return parent::__construct($context);
    }

    public function execute( )
    {

        $resultPage = $this->resultPageFactory->create();


        return $resultPage;

    }

}
