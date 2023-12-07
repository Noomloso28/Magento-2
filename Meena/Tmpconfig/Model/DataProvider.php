<?php
namespace Meena\Tmpconfig\Model;

use Meena\Tmpconfig\Model\ResourceModel\Template\CollectionFactory;

class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $employeeCollectionFactory
     * @param array $meta
     * @param array $data
     */
    
     /**
     * @var array
     */
    protected $_loadedData;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $employeeCollectionFactory,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $employeeCollectionFactory->create();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        
        $this->meta = $this->prepareMeta($meta);
    }
    
    public function prepareMeta(array $meta)
    {
        return $meta;
    }
    

    public function getData()
    {
        if (isset($this->_loadedData)) {
            return $this->_loadedData;
        }
        $items = $this->collection->getItems();
        
        foreach ($items as $employee) {
            
            $result = $employee->getData();
            
            $this->_loadedData[$employee->getId()]['meena_tmpconfig_tempate'] = $result;
            $this->_loadedData[$employee->getId()]['meena_tmpconfig_tempate']['wysiwyg_area'] = array(
                'value' => $result['value']
            );
            
            
        }
        
        
        return $this->_loadedData;
    }
     

    /*
    public function addFilter(\Magento\Framework\Api\Filter $filter)
    {
        return null;
    }
     * */
     
}