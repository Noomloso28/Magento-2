<?php

namespace DesignPattern\MagentoProxy\Model\FeaturedProducts;

class FeaturedProductsSell implements FeaturedProductInterface
{

    protected array $featuredProducts = [];

    public function __construct()
    {
        $this->loadFeaturedProducts();
    }

    protected function loadFeaturedProducts(): void
    {
        $this->featuredProducts = [
            'Sale_1',
            'Sale_2',
            'Sale_3',
            'Sale_4',
            'Sale_5',
            'Sale_6',
            'Sale_7',
        ];
    }

    public function getFeaturedProducts(): array
    {
        return $this->featuredProducts;
    }

    public function count(): int
    {
        return count($this->featuredProducts);
    }
}
