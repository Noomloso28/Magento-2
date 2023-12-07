<?php

namespace Techiz\BannerSlider\Ui\Component\Listing\Column\Group;

use Magento\Framework\Escaper;
use Magento\Framework\Data\OptionSourceInterface;
use Techiz\BannerSlider\Model\GroupFactory as BannerGroupFactory;

/**
 * Class Options
 */
class Options implements OptionSourceInterface
{
    /**
     * Escaper
     *
     * @var Escaper
     */
    protected $escaper;

    /**
     * @var BannerGroupFactory
     */
    protected $bannerGroupFactory;

    /**
     * @var array
     */
    protected $options;

    /**
     * @var array
     */
    protected $currentOptions = [];

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
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        if ($this->options !== null) {
            return $this->options;
        }

        $this->options = $this->getAvailableGroups();

        return $this->options;
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
