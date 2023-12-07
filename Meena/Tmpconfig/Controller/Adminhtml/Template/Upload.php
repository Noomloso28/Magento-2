<?php

namespace Meena\Tmpconfig\Controller\Adminhtml\Template;

use Magento\Framework\Controller\ResultFactory;
use Meena\Tmpconfig\Model\UploadSetup\ImageUploader;

use Meena\Tmpconfig\Model\Session;
/**
 * Class Upload
 */
class Upload extends \Magento\Backend\App\Action
{
    /**
     * Image Uploader
     *
     * @var_\Techiz\BannerSlider\Model\Banner\ImageUploader
     */
    protected $imageUploader;

    /** @var_Session **/
    protected $session;


    /**
     * Upload constructor.
     *
     * @param_\Magento\Backend\App\Action\Context $context
     * @param_Meena\Tmpconfig\Model\UploadSetup\ImageUploader $imageUploader
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        ImageUploader $imageUploader,
        Session $session
    ) {
        parent::__construct($context);
        $this->imageUploader = $imageUploader;
        $this->session = $session;
    }

    /**
     * Authorization level of a basic admin session
     *
     * @return bool
     */
    protected function _isAllowed()
    {

        //return $this->_authorization->isAllowed('Meena_Tmpconfig::template');
        return $this->_authorization->isAllowed('Meena_Tmpconfig::template_save') || $this->_authorization->isAllowed('Meena_Tmpconfig::template_delete');
    }

    /**
     * Upload file controller action.
     *
     * @return_\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $imageId_old = $this->_request->getParam('param_name', 'categories_homepage_image_1');

        $data = $this->_request->getPostValue();
        $imageId = $data['template_configs']['general']['categories_homepage_image_1'];

        $this->session->setData('imageId',  array( 'data' =>$data, 'id' => $imageId , $imageId_old) );

        try {
            $result = $this->imageUploader->saveFileToTmpDir($imageId);

            $result['cookie'] = [
                'name' => $this->_getSession()->getName(),
                'value' => $this->_getSession()->getSessionId(),
                'lifetime' => $this->_getSession()->getCookieLifetime(),
                'path' => $this->_getSession()->getCookiePath(),
                'domain' => $this->_getSession()->getCookieDomain(),
            ];


        } catch (\Exception $e) {
            $result = ['error' => $e->getMessage(), 'errorcode' => $e->getCode()];
        }
        return $this->resultFactory->create(ResultFactory::TYPE_JSON)->setData($result);
    }
}
