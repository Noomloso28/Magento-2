<?php
namespace Boxes\DbActions\Model;
class Post extends \Magento\Framework\Model\AbstractModel implements \Magento\Framework\DataObject\IdentityInterface
{
    const CACHE_TAG = 'Test_DbActions';

    protected $_cacheTag = 'Test_DbActions';

    protected $_eventPrefix = 'Test_DbActions';

    protected function _construct()
    {
        $this->_init('Boxes\DbActions\Model\ResourceModel\Post');
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    public function getDefaultValues()
    {
        $values = [];

        return $values;
    }
}
