<?php

namespace DesignPattern\Factory\Creater;

abstract class AbstractFactoryMethod
{
    abstract public function makeMagentoBook(string $type);
}
