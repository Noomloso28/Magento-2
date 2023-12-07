<?php

namespace DesignPattern\ViewModel\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;

class ProductCollecttion implements ArgumentInterface
{
    /**
     * @var array|mixed
     */
    private $data;

    public function __construct($data=[])
    {
        $this->data = $data;
    }

    public function get()
    {
        return $this->data;
    }
}
