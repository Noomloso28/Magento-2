<?php

namespace DesignPattern\ShapeFactory\Processor\Creating;

abstract class ShapeAbstract implements ShapeInterface
{
    public int $height = 50;
    public int $width = 50;

    /**
     * @param int $height
     */
    public function setHeight(int $height = 1)
    {
        $this->height = $height;
    }

    /**
     * @param int $width
     */
    public function setWidth(int $width = 1)
    {
        $this->width = $width;
    }

    /**
     * @return int
     */
    public function getHeight(): int
    {
        return $this->height;
    }

    /**
     * @return int
     */
    public function getWidth(): int
    {
        return $this->width;
    }

}
