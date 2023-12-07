<?php declare(strict_types=1);

namespace Meena\Tmpconfig\Controller\Adminhtml\Configulation;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;
//use Meena\Tmpconfig\Model\Template;
use Meena\Tmpconfig\Model\TemplateFactory;
use Meena\Tmpconfig\Model\ResourceModel\Template as TemplateResource;
use Magento\Framework\Controller\ResultFactory;


use Meena\Tmpconfig\Model\Session;


class Edit extends Action implements HttpGetActionInterface
{
    const ADMIN_RESOURCE = 'Meena_Tmpconfig::configulation_save';

    /** @var PageFactory */
    protected $pageFactory;
    
    /** @var TemplateFactory */
    protected $templateFactory;

    /** @var TemplateResource */
    protected $templateResource;
    
    /** @var Session **/
    protected $session;

    /**
     * Index constructor.
     * @param Context $context
     * @param PageFactory $pageFactory
     */
    public function __construct( 
            Context $context, 
            PageFactory $pageFactory, 
            TemplateFactory $templateFactory, 
            TemplateResource $templateResource,
            Session $session
    ) {
        parent::__construct($context);
        $this->pageFactory = $pageFactory;
        $this->templateFactory = $templateFactory;
        $this->templateResource = $templateResource;
        $this->session = $session;
    }

    /**
     * @return Page
     */
    public function execute(): Page
    {
        
        $pageid = $this->getRequest()->getParam('id', []);
        
       
        
        if(is_numeric($pageid) && ($pageid > 0)){
            
            /** @var Template $tempate */
            
            
            $tempate = $this->templateFactory->create();
            $this->templateResource->load($tempate, $pageid);
            
            
            $contactDatas = $this->getRequest()->getParam('meena_tmpconfig_tempate');
            
            /* var_dump($contactDatas);
            
            if(is_array($contactDatas)) {
                
                
            }*/
             $getPostValue = $this->getRequest()->getPostValue();
            
            $array_edit = array(
                    'pageid' => $pageid,
                    'isPost' => $this->getRequest()->isPost(),
                    'getParam' => $contactDatas,
                    'getPostValue' => $getPostValue,
                    'getRequest' => $this->getRequest()
            );
            
            //test session
            $this->session->setData('edit_session', $array_edit );
            
            
            
            if( $this->getRequest()->isPost() ){
                
                
                $this->messageManager->addSuccessMessage( __('Successfully added item.') );
                
                // @var Redirect $redirect 
                $redirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
                return $redirect->setPath('*/*');
                
            }
             
            
             
            
        }
        
        
        
        $page = $this->pageFactory->create();
        $page->setActiveMenu('Meena_Tmpconfig::configulation');
        $page->getConfig()->getTitle()->prepend(__('Edit Configulation'));

        return $page;
    }
    
    /*
    public function sessionData()
    {
        $this->session->setData('custom', 'value test');
        echo $this->session->getData('custom');
        //$this->session->unsetData('custom');
        
        
    }
     * 
     */
}
