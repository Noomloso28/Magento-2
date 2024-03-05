<?php
/**
 * FieldMapper test.
 *
 * @package Immerce\FaktumNavConnector\Test\Unit\FieldMapper
 * @version 1.0.0
 * @license GPL 2.0+
 */

declare(strict_types=1);

namespace Immerce\FaktumNavConnector\Test\Unit\FieldMapper;

use Immerce\FaktumNavConnector\FieldMapper\FieldMapper;
use Magento\Framework\Component\ComponentRegistrar;
use Magento\Framework\Component\ComponentRegistrarInterface;
use Magento\Framework\Module\Dir;
use Magento\Framework\Module\Dir\Reader;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Framework\Xml\Parser;
use PHPUnit\Framework\TestCase;

/**
 * FieldMapper test class.
 *
 * @package Immerce\FaktumNavConnector\Test\Unit\FieldMapper
 * @since   1.0.0
 */
class FieldMapperTest extends TestCase
{
    private ObjectManager $objectManager;
    private FieldMapper $fieldMapper;

    protected function setUp(): void
    {
        $this->objectManager = new ObjectManager($this);

        $componentRegistrar = $this->objectManager->getObject(ComponentRegistrar::class);

        $this->fieldMapper = new FieldMapper(
            $this->objectManager->getObject(Parser::class)
        );
        $this->fieldMapper->setDefinitionPath(
            $componentRegistrar->getPath(ComponentRegistrar::MODULE, 'Immerce_FaktumNavConnector') . '/Test/etc/config'
        );
        $this->fieldMapper->loadDefinition('test');
    }

    public function testCanMapFields(): void
    {
        $result = $this->fieldMapper->map([
            'product_number' => '123456',
            'product_details' => 'Lorem ipsum',
        ]);

        $this->assertEquals(
            [
                'sku',
                'description',
            ],
            array_keys($result)
        );
    }
}
