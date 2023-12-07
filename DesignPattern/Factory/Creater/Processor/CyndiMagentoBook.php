<?php

namespace DesignPattern\Factory\Creater\Processor;

class CyndiMagentoBook extends AbstractMagentoBook
{
    private string $author;
    private string $title;

    public function __construct()
    {
        $this->setAuthor('Cyndi Williams');
        $this->setTitle('Magento 2 developer');
    }

    public function getAuthor(): string
    {
        // TODO: Implement getAuthor() method.
        return $this->author;
    }

    public function setAuthor(string $string)
    {
        // TODO: Implement setAuthor() method.
        $this->author = $string;
    }

    public function getTitle(): string
    {
        // TODO: Implement getTitle() method.
        return $this->title;
    }

    public function setTitle(string $string)
    {
        // TODO: Implement setTitle() method.
        $this->title = $string;
    }
}
