<?php

namespace Shop\Glasses\Block\Widget;

use Magento\Framework\View\Element\Template;
use Magento\Widget\Block\BlockInterface;

class PageWidget extends Template implements BlockInterface
{
    protected $_template = "Shop_Glasses::widget/page_widget.phtml";
}
