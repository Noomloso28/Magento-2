<?php declare(strict_types=1);

namespace Meena\Tmpconfig\Model;

use Magento\Framework\Model\AbstractModel;

class Template extends AbstractModel
{
    protected function _construct()
    {
        $this->_init(ResourceModel\Template::class);
    }
}
