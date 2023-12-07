<?php

namespace DesignPattern\ShapeFactory\Controller\Index;

use DesignPattern\ShapeFactory\Processor\Creating\Circle;
use Magento\Framework\App\Action\Action;
use DesignPattern\ShapeFactory\Processor\ShapeFactory;
use Magento\Framework\App\Action\Context;

class Index extends Action
{
    private ShapeFactory $shapeFactory;

    public function __construct(
        Context $context,
        ShapeFactory $shapeFactory
    )
    {
        parent::__construct($context);
        $this->shapeFactory = $shapeFactory;
    }

    public function execute()
    {
        $shape = $this->shapeFactory->getShape('Circle');
        /** @var $shape Circle */
        $shape->setWidth(100);
        $shape->setHeight(150);

        echo $shape->draw();
    }
}
