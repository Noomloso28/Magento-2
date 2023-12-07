<?php
namespace Shop\Freshshop\Model\ResourceModel\Extension;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected function _construct()
    {
        $this->_init('Shop\Freshshop\Model\Extension', 'Shop\Freshshop\Model\ResourceModel\Extension');
    }
}
