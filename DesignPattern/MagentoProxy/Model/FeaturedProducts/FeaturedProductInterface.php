<?php
declare(strict_types=1);

namespace DesignPattern\MagentoProxy\Model\FeaturedProducts;

interface FeaturedProductInterface
{
    /**
     * @return array
     */
    public function getFeaturedProducts(): array;

    /**
     * @return int
     */
    public function count(): int;
}
