<?php

namespace DesignPattern\ViewModel\Model;

use Magento\Framework\View\Element\Block\ArgumentInterface;

class VirtualType implements ArgumentInterface
{
    /**
     * @var array|mixed
     */
    private $getFruitsObject;

    public function __construct($data=[])
    {
        $this->getFruitsObject = $data;
    }

    public function getFruit()
    {
        return $this->getFruitsObject;
    }

}
