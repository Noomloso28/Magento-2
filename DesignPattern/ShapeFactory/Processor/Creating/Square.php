<?php

namespace DesignPattern\ShapeFactory\Processor\Creating;

class Square extends ShapeAbstract implements DrawableShapeInterface
{
    public function draw(): string
    {
        // TODO: Implement draw() method.
        return "Create Square has width = $this->width & height = $this->height";
    }

}
