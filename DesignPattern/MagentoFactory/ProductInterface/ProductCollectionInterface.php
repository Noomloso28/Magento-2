<?php

namespace DesignPattern\MagentoFactory\ProductInterface;

interface ProductCollectionInterface
{
    /**
     * @param string $name
     * @return mixed
     */
    public function setName(string $name);

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @param $sku
     * @return mixed
     */
    public function setSku($sku);

    /**
     * @return mixed
     */
    public function getSku();

    /**
     * @param float $price
     * @return mixed
     */
    public function setPrice(float $price);

    /**
     * @return float
     */
    public function getPrice(): float;

}
