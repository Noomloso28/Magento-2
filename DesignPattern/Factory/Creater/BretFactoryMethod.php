<?php

namespace DesignPattern\Factory\Creater;
use DesignPattern\Factory\Creater\Processor\BretMagentoBook;
use DesignPattern\Factory\Creater\Processor\CyndiMagentoBook;

class BretFactoryMethod extends AbstractFactoryMethod
{
    private BretMagentoBook $bretMagentoBook;
    private CyndiMagentoBook $cyndiMagentoBook;

    public function __construct(
        BretMagentoBook $bretMagentoBook,
        CyndiMagentoBook $cyndiMagentoBook
    )
    {
        $this->bretMagentoBook = $bretMagentoBook;
        $this->cyndiMagentoBook = $cyndiMagentoBook;
    }

    public function makeMagentoBook(string $type)
    {
        // TODO: Implement makeMagentoBook() method.

        switch ($type){
            case 1:
                $book = $this->bretMagentoBook;
                break;
            case 2:
                $book = $this->cyndiMagentoBook;
                break;
            default:
                $book = $this->bretMagentoBook;
        }
        return $book;
    }
}
