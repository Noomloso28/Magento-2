<?php

namespace DesignPattern\Roles\Model\ResourceModel;

use Magento\Rule\Model\ResourceModel\AbstractResource;

class Rule extends AbstractResource
{

    /**
     * Initialize main table and table id field
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('designpattern_rules', 'rule_id');
    }
}
