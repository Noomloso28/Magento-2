<?php

namespace Techiz\BannerSlider\Model\Group\Config\Source;

use Magento\Framework\Escaper;
use Techiz\BannerSlider\Model\GroupFactory as BannerGroupFactory;

class Options implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @var BannerGroupFactory
     */
    protected $bannerGroupFactory;

    /**
     * Escaper
     *
     * @var Escaper
     */
    protected $escaper;

    /**
     * Constructor
     *
     * @param BannerGroupFactory $systemStore
     * @param Escaper $escaper
     */
    public function __construct(BannerGroupFactory $bannerGroupFactory, Escaper $escaper)
    {
        $this->bannerGroupFactory = $bannerGroupFactory;
        $this->escaper = $escaper;
    }

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return $this->getAvailableGroups();
    }

    /**
     * Prepare groups
     *
     * @return array
     */
    private function getAvailableGroups()
    {
        $collection = $this->bannerGroupFactory->create()->getCollection();
        $result = [];
        $result[] = ['value' => ' ', 'label' => 'Select...'];
        foreach ($collection as $group) {
            $result[] = ['value' => $group->getId(), 'label' => $this->escaper->escapeHtml($group->getName())];
        }
        return $result;
    }
}
