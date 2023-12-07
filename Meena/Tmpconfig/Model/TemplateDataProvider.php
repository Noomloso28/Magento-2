<?php
namespace Meena\Tmpconfig\Model;

use Meena\Tmpconfig\Model\ResourceModel\Template\CollectionFactory;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;
use Magento\Framework\App\RequestInterface;



class TemplateDataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * @param_string $name
     * @param_string $primaryFieldName
     * @param_string $requestFieldName
     * @param_CollectionFactory $employeeCollectionFactory
     * @param_array $meta
     * @param_array $data
     */
    protected $collection;

     /**
     * @var array
     */
    protected $_loadedData;

    protected $request;

    protected $_storeManager;

    /** @var_Filesystem  */
    protected $filesystem;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $CollectionFactory,
        array $meta = [],
        array $data = [],
        RequestInterface $request,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        Filesystem $filesystem
    ) {
        $this->collection = $CollectionFactory->create();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);

        $this->request = $request;
        $this->_storeManager=$storeManager;
        $this->filesystem = $filesystem;
    }

   /* public function prepareMeta(array $meta)
    {
        return $meta;
    }
*/

    public function getData()
    {
        if (isset($this->_loadedData)) {
            return $this->_loadedData;
        }


        $items = $this->collection->getItems();
        $results = array();
        $id = $this->request->getParam('id');

        /*
         * All URL command
         */
        $baseurl =  rtrim( $this->_storeManager->getStore()->getBaseUrl() ,"/");
        $mediapath = rtrim(  $this->filesystem->getDirectoryRead(DirectoryList::PUB)->getAbsolutePath() ,"/");



        foreach ($items as $employee) {

            $result = $employee->getData();
            $results[$result['name']] = $result['value'];

            /*
             * Check in case Image value
             */
            if (is_file( $mediapath.$result['value']  ) ) {

                $results[$result['name']] = $this->getFileDetails( $baseurl.$result['value'] , $mediapath.$result['value']  );

            }


        }

        /*
         * Fist Tab varible (general'tab)
         */
        $this->_loadedData[$id]['template_configs']['general']  = $results;




        return $this->_loadedData;

    }

    private function getFileDetails( $baseUrl = null ,$media_url = null ){

        $pathinfo = pathinfo( $baseUrl );

        return  array(
                        0 => array(
                            'name' => $pathinfo['basename'],
                            'url' => $baseUrl,
                            'size' => filesize( $media_url ),
                            'type' => mime_content_type( $media_url )
                             )
                    );

    }



/*
    public function addFilter(\Magento\Framework\Api\Filter $filter)
    {
        return null;
    }
*/



}
