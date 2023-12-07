<?php declare(strict_types=1);

namespace Meena\Tmpconfig\Controller\Adminhtml\Configulation;

use Meena\Tmpconfig\Model\Template;
use Meena\Tmpconfig\Model\TemplateFactory;
use Meena\Tmpconfig\Model\ResourceModel\Template as TemplateResource;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultFactory;

class Delete extends Action implements HttpGetActionInterface
{
    const ADMIN_RESOURCE = 'Meena_Tmpconfig::configulation_delete';

    /** @var TemplateFactory */
    protected $templateFactory;

    /** @var TemplateResource */
    protected $templateResource;

    /**
     * Delete constructor.
     * @param Context $context
     * @param FaqFactory $faqFactory
     * @param FaqResource $faqResource
     */
    public function __construct(
        Context $context,
        TemplateFactory $templateFactory,
        TemplateResource $templateResource
    ) {
        $this->templateFactory = $templateFactory;
        $this->templateResource = $templateResource;
        parent::__construct($context);
    }

    public function execute(): Redirect
    {
        try {
            $id = $this->getRequest()->getParam('id');

            /** @var Template $template */
            $template = $this->templateFactory->create();

            $this->templateResource->load($template, $id);
            if ($template->getId()) {
                $this->templateResource->delete($template);
                $this->messageManager->addSuccessMessage(__('The record has been deleted.'));
            } else {
                $this->messageManager->addErrorMessage(__('The record does not exist.'));
            }
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }

        /** @var Redirect $redirect */
        $redirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

        return $redirect->setPath('*/*');
    }
}
