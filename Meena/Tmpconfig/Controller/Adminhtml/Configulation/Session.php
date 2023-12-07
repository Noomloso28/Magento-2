<?php declare(strict_types=1);

namespace Meena\Tmpconfig\Controller\Adminhtml\Configulation;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;

class Session extends Action implements HttpGetActionInterface
{
    const ADMIN_RESOURCE = 'Meena_Tmpconfig::configulation';

    /** @var PageFactory */
    protected $pageFactory;

    /**
     * Index constructor.
     * @param Context $context
     * @param PageFactory $pageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $pageFactory
    ) {
        parent::__construct($context);
        $this->pageFactory = $pageFactory;
    }

    /**
     * @return Page
     */
    public function execute(): Page
    {
        $page = $this->pageFactory->create();
        
        $page->setActiveMenu('Meena_Tmpconfig::session');
        $page->getConfig()->getTitle()->prepend(__('Session Manager'));
        
        

        return $page;

    }
}
