<?php declare(strict_types=1);

namespace Meena\Tmpconfig\Model\ResourceModel\Template;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Meena\Tmpconfig\Model\Template;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(Template::class, \Meena\Tmpconfig\Model\ResourceModel\Template::class);
    }
}
