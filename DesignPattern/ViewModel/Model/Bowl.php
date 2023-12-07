<?php

namespace DesignPattern\ViewModel\Model;

class Bowl
{
    /**
     * @var array|mixed
     */
    private $fruits;

    public function __construct($fruits=[])
    {
        $this->fruits = $fruits;
    }
}
