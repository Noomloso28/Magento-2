<?php
namespace Boxes\BestsellerEachCategories\Controller\Index;

use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;


class Index extends \Magento\Framework\App\Action\Action{

    /**
     * @var PageFactory
     */
    private PageFactory $pageFactory;


    public function __construct(
        Context $context,
        PageFactory $pageFactory
    )
    {
        $this->pageFactory = $pageFactory;

        parent::__construct($context);
    }

    public function execute()
    {
        // TODO: Implement execute() method.


        return $this->pageFactory->create();
    }
}
