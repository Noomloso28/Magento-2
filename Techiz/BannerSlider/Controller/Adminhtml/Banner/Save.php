<?php

namespace Techiz\BannerSlider\Controller\Adminhtml\Banner;

use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;
use Techiz\BannerSlider\Model\Banner\ImageUploader;

class Save extends \Magento\Backend\App\Action
{
    /**
     * @var_DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var_ImageUploader
     */
    protected $imageUploader;

    /**
     * @param_Context $context
     * @param_DataPersistorInterface $dataPersistor
     * @param_ImageUploader $imageUploader
     */
    public function __construct(
        Context $context,
        DataPersistorInterface $dataPersistor,
        ImageUploader $imageUploader
    ) {
        $this->dataPersistor = $dataPersistor;
        $this->imageUploader = $imageUploader;
        parent::__construct($context);
    }

    /**
     * Save action
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @return_\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var\Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();

        if ($data) {
            $id = $this->getRequest()->getParam('id');

            if (empty($data['id'])) {
                $data['id'] = null;
            }

            $imageName = '';
            if (!empty($data['image'])) {
                $imageName = $data['image'][0]['name'];
            }

            /** @var\Techiz\BannerSlider\Model\Banner $model */
            $model = $this->_objectManager->create('Techiz\BannerSlider\Model\Banner')->load($id);
            if (!$model->getId() && $id) {
                $this->messageManager->addError(__('This banner no longer exists.'));
                return $resultRedirect->setPath('*/*/');
            }

            $model->setData($data);

            try {
                $model->save();
                if ($imageName) {
                    $this->imageUploader->moveFileFromTmp($imageName);
                }
                $this->messageManager->addSuccess(__('You saved the banner.'));
                $this->dataPersistor->clear('banners_slider');

                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId()]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the banner.'));
            }

            $this->dataPersistor->set('banners_slider', $data);
            return $resultRedirect->setPath('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }

    /**
     * Authorization level of a basic admin session
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Techiz_BannerSlider::banner_update') || $this->_authorization->isAllowed('Techiz_BannerSlider::banner_create');
    }
}
