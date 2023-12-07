<?php

namespace DesignPattern\ShapeFactory\Processor;


use DesignPattern\ShapeFactory\Processor\Creating\Circle;
use DesignPattern\ShapeFactory\Processor\Creating\Rectangle;
use DesignPattern\ShapeFactory\Processor\Creating\ShapeInterface;
use DesignPattern\ShapeFactory\Processor\Creating\Square;

class ShapeFactory implements ShapeFactoryInterface
{
    public function getShape($type): ShapeInterface
    {
        switch ($type) {

            case "Squery":
                $execute = new Square();
                break;

            case "Rectangle":
                $execute = new Rectangle();
                break;

            default:
                $execute = new Circle();
                break;
        }

        return $execute;
    }
}
