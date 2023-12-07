<?php
namespace Shop\Freshshop\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
class Extension extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('Shop_freshShop_template_config', 'config_id');
    }
}
