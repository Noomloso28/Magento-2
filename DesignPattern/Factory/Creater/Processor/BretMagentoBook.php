<?php

namespace DesignPattern\Factory\Creater\Processor;

class BretMagentoBook extends AbstractMagentoBook
{

    private string $author;
    private string $title;

    public function __construct()
    {
        $this->setAuthor('Bret Williams');
        $this->setTitle('Magento 1 developer');
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
