<?php
namespace Boxes\Pickcolor\Controller\Index;

use Magento\Framework\Controller\ResultFactory;

class Index extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $_pageFactory;
    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $request;



	public function __construct(
		\Magento\Framework\App\Action\Context $context,
		\Magento\Framework\View\Result\PageFactory $pageFactory,
        \Magento\Framework\App\RequestInterface $request
	)
	{
		$this->_pageFactory = $pageFactory;
        $this->request = $request;
		return parent::__construct($context);
	}

	public function execute()
	{
		$resultPage = $this->_pageFactory->create();
		$resultPage->getConfig()->getTitle()->set('PickColor Index Page');
		return $resultPage;
	}
}
