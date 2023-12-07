<?php declare(strict_types=1);

namespace Meena\Tmpconfig\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Template extends AbstractDb
{
    /** @var string Main table name */
    const MAIN_TABLE = 'meena_tmpconfig_tempate';

    /** @var string Main table primary key field name */
    const ID_FIELD_NAME = 'id';

    protected function _construct()
    {
        $this->_init(self::MAIN_TABLE, self::ID_FIELD_NAME);
    }
}
