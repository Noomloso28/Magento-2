<?php

namespace DesignPattern\ViewModel\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;

class ProductProvider implements ArgumentInterface
{
    public function get()
    {
        return 'test viewModel';
    }
}
