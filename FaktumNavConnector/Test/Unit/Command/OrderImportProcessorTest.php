<?php

namespace Immerce\FaktumNavConnector\Console\Command;

use PHPUnit\Framework\TestCase;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;

class OrderImportProcessorTest extends TestCase
{
    private ObjectManager $_objectManager;

    private $orderImportProcessor;

    public function setUp(): void
    {
        $this->_objectManager = new ObjectManager($this);
        $this->orderImportProcessor = $this->_objectManager->getObject('Immerce\FaktumNavConnector\Console\Command\OrderImportProcessor');

    }
    public function testIsEnabled()
    {
        $result = $this->orderImportProcessor->isEnabled();
        $this->assertEquals(true, $result);
    }
}
