<?php
namespace Boxes\Pickcolor\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
class Extension extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('Boxes_pickcolor_db', 'id');
    }
}
