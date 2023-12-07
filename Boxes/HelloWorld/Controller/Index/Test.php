<?php
namespace Boxes\HelloWorld\Controller\Index;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\ResultFactory;

class Test extends \Magento\Framework\App\Action\Action
{
    protected $_pageFactory;
    private $getRequestURL;


    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $pageFactory,
        RequestInterface $RequestInterface
    )
    {
        $this->_pageFactory = $pageFactory;
        $this->getRequestURL = $RequestInterface;

        return parent::__construct($context);


    }

    public function execute( )
    {

        //var_dump($this->getActionFlag()->get( '', 'no-dispatch', true ));
        //var_dump($this->getRequest());

        $RequestInterfaceArray = $this->getRequestURL( $this->getRequestURL);


        /** @var Page $page */
        $page = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        /** @var Template $block */
        $block = $page->getLayout()->getBlock('helloworld_index_test');
        $block->setData('custom_parameter', $RequestInterfaceArray );


        return $page;
    }

    private function getRequestURL(RequestInterface $RequestInterface ){

        $action = strtolower($RequestInterface->getActionName());
        $router = strtolower($RequestInterface->getFullActionName());
        $getModuleName = $RequestInterface->getModuleName();
        $getParams = $RequestInterface->getParams();

        $RequestInterfaceArray = array(
            'action' => $action,
            'route' => $router,
            'getModuleName' => $getModuleName,
            'getParams' => $getParams
        );
       /* echo '<pre>';
        print_r($RequestInterfaceArray);
        echo '</pre>';
       */
        return $RequestInterfaceArray;
    }
}
