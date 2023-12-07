<?php
namespace Boxes\Pickcolor\Model\ResourceModel\Extension;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected function _construct()
    {
        $this->_init('Boxes\Pickcolor\Model\Extension', 'Boxes\Pickcolor\Model\ResourceModel\Extension');
    }
}
