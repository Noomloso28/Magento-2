<?php declare(strict_types=1);

namespace Meena\Tmpconfig\Controller\Adminhtml\Configulation;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Meena\Tmpconfig\Model\Template;
use Meena\Tmpconfig\Model\TemplateFactory;
use Meena\Tmpconfig\Model\ResourceModel\Template as TemplateResource;

class InlineEdit extends Action implements HttpPostActionInterface
{
    const ADMIN_RESOURCE = 'Meena_Tmpconfig::configulation_save';

    /** @var JsonFactory */
    protected $jsonFactory;

    /** @var FaqFactory */
    protected $templateFactory;

    /** @var FaqResource */
    protected $templateResource;

    /**
     * InlineEdit constructor.
     * @param Context $context
     * @param JsonFactory $jsonFactory
     * @param TemplateFactory $templateFactory
     * @param TemplateResource $templateResource
     */
    public function __construct(
        Context $context,
        JsonFactory $jsonFactory,
        TemplateFactory $templateFactory,
        TemplateResource $templateResource
    ) {
        parent::__construct($context);
        $this->jsonFactory = $jsonFactory;
        $this->templateFactory = $templateFactory;
        $this->templateResource = $templateResource;
    }

    public function execute()
    {
        $json = $this->jsonFactory->create();
        $messages = [];
        $error = false;
        $isAjax = $this->getRequest()->getParam('isAjax', false);
        $items = $this->getRequest()->getParam('items', []);

        if (!$isAjax || !count($items)) {
            $messages[] = __('Please correct the data sent.');
            $error = true;
        }

        if (!$error) {
            foreach ($items as $item) {
                $id = $item['id'];
                try {
                    /** @var Template $tempate */
                    $tempate = $this->templateFactory->create();
                    $this->templateResource->load($tempate, $id);
                    $tempate->setData(array_merge($tempate->getData(), $item));
                    $this->templateResource->save($tempate);
                } catch (\Exception $e) {
                    $messages[] = __("Something went wrong while saving item $id");
                    $error = true;
                }
            }
        }

        return $json->setData([
            'messages' => $messages,
            'error' => $error,
        ]);
    }
}
