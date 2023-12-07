<?php
namespace Meena\Tmpconfig\Block\Frontend\Homepage;

use Magento\Framework\View\Element\Template;
use Magento\Cms\Model\Template\FilterProvider;

use Meena\Tmpconfig\Model\ResourceModel\Template\CollectionFactory;
//use Magento\Widget\Block\BlockInterface;

//implements BlockInterface
class CategoriesHomepage extends Template
{

    /**@var_CollectionFactory */
    protected $collection;

    /**
     * @var \Magento\Cms\Model\Template\FilterProvider
     */
    protected $_filterProvider;



    public function __construct(
        Template\Context $context,
        array $data = array(),
        CollectionFactory $CollectionFactory,
        FilterProvider $filterProvider
    )
    {

        $this->_filterProvider = $filterProvider;
        parent::__construct($context, $data);

        $this->collection = $CollectionFactory->create();

    }


    /**
     * Prepare HTML content
     *
     * @return string
     */
    private function getCmsFilterContent($value='')
    {
        $html = $this->_filterProvider->getPageFilter()->filter($value);
        return $html;
    }


    public function getText(){
        return 'Custom text';
    }


    public function displayValue(){

        $items = $this->collection->getItems();

        return  $this->getDataDB( $items );
    }



    private function  getDataDB( $allArray = array() ){

        $result = array();

        foreach ($allArray as $values) {

            $array = $values->getData();

            $result[$array['name']] = $this->getCmsFilterContent( $array['value'] );

        }

        return  json_decode(json_encode($result));
    }

}
