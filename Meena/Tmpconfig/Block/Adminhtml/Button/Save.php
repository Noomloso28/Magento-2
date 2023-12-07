<?php

namespace Meena\Tmpconfig\Block\Adminhtml\Button;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Meena\Tmpconfig\Block\Adminhtml\Button\Generic;
use Magento\Ui\Component\Control\Container;

class Save extends Generic implements ButtonProviderInterface
{
    public function getButtonData()
    {
        
        //$url = $this->getUrl('tmpconfig/configulation/edit');
        
        return [
            'label' => __('Save'),
            'class' => 'save primary',
            'data_attribute' => [
                'mage-init' => [
                    'buttonAdapter' => [
                        'actions' => [
                            [
                                'targetName' => 'tmpconfig_configulation_edit.tmpconfig_configulation_edit',
                                'actionName' => 'save',
                                'params' => [
                                    false
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            //'url' => $url
            /*'data_attribute' => [
                'mage-init' => [
                    'button' => [
                        'event' => 'save'
                    ]
                ],
                'form-role' => 'save',
            ],*/
        ];
        
    }

}
