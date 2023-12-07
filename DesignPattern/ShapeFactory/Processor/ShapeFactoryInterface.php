<?php

namespace DesignPattern\ShapeFactory\Processor;

use DesignPattern\ShapeFactory\Processor\Creating\ShapeInterface;

interface ShapeFactoryInterface
{
    /**
     * @param string $type
     * @return ShapeInterface
     */
    public function getShape(string $type) :ShapeInterface;
}
