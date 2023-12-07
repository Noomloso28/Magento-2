<?php

namespace DesignPattern\MagentoFactory\Block;

use Magento\Framework\View\Element\Template;

class ProductCollection extends Template
{
    /**
     * @var mixed
     */
    private $name;

    public function __construct(Template\Context $context, array $data = [])
    {
        $this->name = $data['name'] ?? '';

        parent::__construct($context, $data);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }


}
