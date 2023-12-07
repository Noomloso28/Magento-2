<?php

namespace DesignPattern\Factory\Creater\Processor;

abstract class AbstractBook
{
    /**
     * @return string
     */
    abstract public function getAuthor(): string;

    /**
     * @param string $string
     * @return mixed
     */
    abstract public function setAuthor(string $string);

    /**
     * @return string
     */
    abstract public function getTitle(): string;

    /**
     * @param string $string
     * @return mixed
     */
    abstract public function setTitle(string $string);
}
