<?php declare(strict_types=1);

namespace Boxes\JqueryImageUpload\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Result\PageFactory;


class Index extends Action
{

    /**
     * @var ResultFactory
     */
    protected $resultFactory;
    /**
     * @var RequestInterface
     */
    protected $_requestInterface;
    protected $pageFactory;

    public function __construct(
        Context $context,
        ResultFactory $resultFactory,
        RequestInterface $_requestInterface,
        PageFactory $pageFactory
    )
    {
        $this->resultFactory = $resultFactory;
        $this->_requestInterface = $_requestInterface;
        $this->pageFactory = $pageFactory;

        parent::__construct($context);
    }

    public function execute()
    {
        // TODO: Implement execute() method.
        $resultPage = $this->pageFactory->create();
        $resultPage->getConfig()->getTitle()->set('Image upload by jQuery');

        return $resultPage;
    }
}
