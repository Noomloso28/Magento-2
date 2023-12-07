<?php declare(strict_types=1);

namespace Meena\Tmpconfig\Setup\Patch\Data;

use Meena\Tmpconfig\Model\ResourceModel\Template;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchInterface;

class InitialTemplate implements DataPatchInterface
{
    /** @var ModuleDataSetupInterface */
    protected $moduleDataSetup;

    /** @var ResourceConnection */
    protected $resource;

    /**
     * InitialFaqs constructor.
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param ResourceConnection $resource
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        ResourceConnection $resource
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->resource = $resource;
    }

    public static function getDependencies()
    {
        return [];
    }

    public function getAliases()
    {
        return [];
    }

    public function apply(): self
    {
        $connection = $this->resource->getConnection();
        $data = [
            [
                'Name' => 'What is your best selling item?',
                'Value' => 'The item you buy!',
                'is_published' => 1,
            ],
            [
                'Name' => 'What is your customer support number?',
                'Value' => '212-867-5309. Ask for Jenny!',
                'is_published' => 1,
            ],
            [
                'Name' => 'When will I get my order?',
                'Value' => 'When it gets delivered, silly!',
                'is_published' => 0,
            ],
        ];
        $connection->insertMultiple(Template::MAIN_TABLE, $data);

        return $this;
    }
}
