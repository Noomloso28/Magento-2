<?php

namespace DesignPattern\ShapeFactory\Processor\Creating;

class Circle extends ShapeAbstract implements DrawableShapeInterface
{

    public function draw(): string
    {
        return "Create Circle has width = ".$this->getWidth()." & height = ".$this->getHeight();
    }

}
