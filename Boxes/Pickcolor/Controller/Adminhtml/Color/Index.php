<?php
namespace Boxes\Pickcolor\Controller\Adminhtml\Color;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

use Boxes\Pickcolor\Model\Session;

class Index extends  Action {


    /**
     * @var PageFactory
     */
    protected $pageFactory =false;
    /**
     * @var Session
     */
    protected $session;


    public function __construct(
        Context $context,
        PageFactory $pageFactory,
        Session $session
    )
    {
        parent::__construct($context);
        $this->pageFactory = $pageFactory;
        $this->session = $session;
    }

    public function execute()
    {
        $resultPage = $this->pageFactory->create();
        $resultPage->setActiveMenu('Boxes_Pickcolor::pickcolor');
        $resultPage->getConfig()->getTitle()->prepend(__('Attributes picker Configulations'));

        $this->session->setData('imageId',  array( 'data' =>'test', 'id' => 'test2' ) );


        return $resultPage;
    }


    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Boxes_Pickcolor::pickcolor');
    }



}
