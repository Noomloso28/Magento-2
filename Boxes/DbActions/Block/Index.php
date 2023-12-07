<?php
namespace Boxes\DbActions\Block;
class Index extends \Magento\Framework\View\Element\Template
{
    private \Boxes\DbActions\Model\PostFactory $_postFactory;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Boxes\DbActions\Model\PostFactory $postFactory
    )
    {
        $this->_postFactory = $postFactory;
        parent::__construct($context);
    }

    public function sayHello()
    {
        return __('Hello World');
    }

    public function getPostCollection(){
        $post = $this->_postFactory->create();
        return $post->getCollection();
    }

}
