<?php declare(strict_types=1);

namespace Meena\Tmpconfig\Controller\Adminhtml\Template;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;

use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;

use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;
use Meena\Tmpconfig\Model\Session;
use Meena\Tmpconfig\Model\ResourceModel\Template\CollectionFactory;

use Meena\Tmpconfig\Model\Template;
use Meena\Tmpconfig\Model\TemplateFactory;
use Meena\Tmpconfig\Model\ResourceModel\Template as TemplateResource;

use Meena\Tmpconfig\Model\UploadSetup\ImageUploader;

use Magento\Framework\Filesystem;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Store\Model\StoreManagerInterface;

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

    /** @var_Session **/
    protected $session;

    /**@var_CollectionFactory */
    protected $collection;

    /** @var_FaqResource */
    protected $templateResource;

    /** @var_templateFactory */
    protected $templateFactory;

    /** Filesystem  */
    protected $filesystem;

    /** @param_\Magento\Store\Model\StoreManagerInterface   */
    protected $_storeManager;

    /**
     * @param_Context $context
     * @param_DataPersistorInterface $dataPersistor
     * @param_ImageUploader $imageUploader
     */
    public function __construct(
        Context $context,
        DataPersistorInterface $dataPersistor,
        CollectionFactory $CollectionFactory,
        TemplateResource $templateResource,
        TemplateFactory $templateFactory,
        ImageUploader $imageUploader,
        Filesystem $filesystem,
        StoreManagerInterface $storeManager,
        Session $session
    ) {
        $this->dataPersistor = $dataPersistor;
        parent::__construct($context);
        $this->collection = $CollectionFactory->create();
        $this->templateResource = $templateResource;
        $this->templateFactory = $templateFactory;
        $this->imageUploader = $imageUploader;
        $this->filesystem = $filesystem;
        $this->_storeManager=$storeManager;
        $this->session = $session;

    }


    public function execute()
    {

        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();

        //test session
        $this->session->setData('data_post',  $data );

        $this->CheckDataBeforeSaveDB( $data );


        return $resultRedirect->setPath('*/*/index/id/1');

    }


    private function CheckDataBeforeSaveDB( $array = array() ){

        //check first Tab.
        if( isset($array['template_configs']['general']) ){

            $values = $array['template_configs']['general'];

            foreach ($values as $key => $value ){
                //$data['value'] = $value;
                $where['name'] = $key;
                $old_value = $this->getDataDB( $key , 'value');
                $id = $this->getDataDB( $key , 'id');
                /*
                 * Check image type before save.
                 */
                if( is_array($value)){
                    //check upload type before save
                    $type_url = $this->checkDataCurentType( $value );
                    if( isset($type_url)){
                        $value = $type_url;
                    }

                    //test session
                    //$this->session->setData('data_post_'.$key, $type_url );
                }



                if( ("$value" !== "$old_value") && ($id > 0) ){

                    try {

                        $data = array(
                            'value' => $value
                        );

                        /** @var_Template $tempate */
                        $tempate = $this->templateFactory->create();
                        $this->templateResource->load($tempate, $id);
                        $tempate->setData(array_merge($tempate->getData(),  $data ));
                        $this->templateResource->save($tempate);


                        $this->messageManager->addSuccess(__("Updated the items were successful."));

                    } catch (\Exception $e) {

                        $this->messageManager->addException($e, __("Something went wrong while saving item $id"));
                        //$error = true;
                    }

                }
            }
        }

    }
    /*
     * Check other type not String
     */
    private function checkDataCurentType( $array = array() ){

        //$mediapath = $this->filesystem->getDirectoryRead(DirectoryList::MEDIA)->getAbsolutePath();

        foreach ($array as $key ){

            /*
             * Check if image type return URL
             */
            if( isset($key['url']) ) {

                $baseurl =  rtrim( $this->_storeManager->getStore()->getBaseUrl() ,"/");
                $url = str_replace($baseurl,"",$key['url']);


                return $url;
            }
        }

        return null;
    }

    private function  getDataDB( $key = null , $field = null ){

        $items = $this->collection->getItems();
        foreach ($items as $values) {

            $result = $values->getData();
            if( $result['name'] == $key ){

                return $result[$field];
            }
        }

        return  0;
    }


    /**
     * Authorization level of a basic admin session
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Meena_Tmpconfig::template_save') || $this->_authorization->isAllowed('Meena_Tmpconfig::template_delete');
    }
}
