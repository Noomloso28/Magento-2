<?php

namespace DesignPattern\MagentoProxy\ViewModel;

use DesignPattern\MagentoProxy\Model\FeatureProducts;
use DesignPattern\MagentoProxy\Model\FeatureProductsFactory;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class FeaturedProductViewModel implements ArgumentInterface
{
    private FeatureProducts $featureProducts;

    /**
     * @param FeatureProducts $featureProducts
     */
    public function __construct(FeatureProductsFactory $featureProducts)
    {
        $this->featureProducts = $featureProducts->create();
    }
    public function getFeaturedProducts(): array
    {
        return $this->featureProducts->getFeaturedProducts();
    }
}
