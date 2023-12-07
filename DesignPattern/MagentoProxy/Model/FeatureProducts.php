<?php
declare(strict_types=1);

namespace DesignPattern\MagentoProxy\Model;

use DesignPattern\MagentoProxy\Model\FeaturedProducts\FeaturedProductCategories;
use DesignPattern\MagentoProxy\Model\FeaturedProducts\FeaturedProductsSell;

class FeatureProducts
{
    private FeaturedProductCategories $featuredProductCategories;
    private FeaturedProductsSell $featuredProductsSell;

    /**
     * @param FeaturedProductCategories $featuredProductCategories
     * @param FeaturedProductsSell $featuredProductsSell
     */
    public function __construct(
        FeaturedProductCategories $featuredProductCategories,
        FeaturedProductsSell $featuredProductsSell
    ) {
        $this->featuredProductCategories = $featuredProductCategories;
        $this->featuredProductsSell = $featuredProductsSell;
    }

    public function getFeaturedProducts(): array
    {
        if($this->featuredProductCategories->count() < 6){
            return $this->featuredProductsSell->getFeaturedProducts();
        }

        return $this->featuredProductCategories->getFeaturedProducts();
    }
}
