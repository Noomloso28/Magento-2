<?php

namespace DesignPattern\ShapeFactory\Processor\Creating;

class Rectangle extends ShapeAbstract implements DrawableShapeInterface
{

    public function draw(): string
    {
        // TODO: Implement draw() method.
        return "Create Rectangle has width = $this->width & height = $this->height";
    }

}
