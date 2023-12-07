<?php

namespace DesignPattern\Factory\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use DesignPattern\Factory\Creater\BretFactoryMethod;

class Index extends Action
{
    private BretFactoryMethod $bretFactoryMethod;

    public function __construct(
        Context $context,
        BretFactoryMethod $bretFactoryMethod
    )
    {
        parent::__construct($context);

        $this->bretFactoryMethod = $bretFactoryMethod;
    }

    public function execute()
    {
        // TODO: Implement execute() method.
        var_dump('test designpatternfactory');
        $bretFactoryMethod = $this->bretFactoryMethod->makeMagentoBook(2);
        $title = $bretFactoryMethod->getTitle();
        var_dump($title);
    }
}
