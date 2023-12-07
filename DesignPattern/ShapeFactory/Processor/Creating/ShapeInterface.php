<?php

namespace DesignPattern\ShapeFactory\Processor\Creating;

interface ShapeInterface
{
    /**
     * @param int $height
     * @return mixed
     */
    public function setHeight(int $height);

    /**
     * @return int
     */
    public function getHeight(): int;

    /**
     * @return int
     */
    public function getWidth(): int;

    /**
     * @param int $width
     * @return mixed
     */
    public function setWidth(int $width);

}
