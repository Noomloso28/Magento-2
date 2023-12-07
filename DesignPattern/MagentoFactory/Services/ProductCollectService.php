<?php

namespace DesignPattern\MagentoFactory\Services;

use DesignPattern\MagentoFactory\ProductInterface\ProductCollectionInterface;

class ProductCollectService implements ProductCollectionInterface
{
    private string $name;
    private $sku;
    private float $price;
    /**
     * @inheritDoc
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @inheritDoc
     */
    public function setSku($sku)
    {
        $this->sku = $sku;
    }

    /**
     * @inheritDoc
     */
    public function setPrice(float $price)
    {
        $this->price = $price;
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    public function getSku()
    {
        return $this->sku;
    }

    /**
     * @inheritDoc
     */
    public function getPrice(): float
    {
        return $this->price;
    }
}
