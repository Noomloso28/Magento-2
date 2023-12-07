<?php

namespace Shop\Freshshop\Setup;


use \Magento\Framework\Setup\UpgradeDataInterface;
use \Magento\Framework\Setup\ModuleContextInterface;
use \Magento\Framework\Setup\ModuleDataSetupInterface;

/**
 * Class UpgradeData
 *
 */
class UpgradeData implements UpgradeDataInterface
{

    /**
     * Creates data to DB
     *
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        $tableName = $setup->getTable('Shop_freshShop_template_config');

        if ($setup->getConnection()->isTableExists($tableName) == true) {

            $data = [
                [
                    'name' => 'content title',
                    'value' => 'Content of the first post.',
                    'type' => 'description',
                    'sort' => 2
                ],
                [
                    'name' => 'name Title 2',
                    'value' => 'Content of the second post.',
                    'type' => 'description2',
                    'sort' => 1
                ],
                [
                    'name' => 'name Title 3',
                    'value' => 'Content of the third post.',
                    'type' => 'description3',
                    'sort' => 1,
                    'status' => 1
                ],
            ];

            $setup
                ->getConnection()
                ->insertMultiple($tableName, $data);
        }

        $setup->endSetup();
    }
}
