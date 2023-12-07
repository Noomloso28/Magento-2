<?php
namespace Boxes\DbActions\Controller\Index;

class Events extends \Magento\Framework\App\Action\Action
{

    public function execute()
    {
        $textDisplay = new \Magento\Framework\DataObject(array('text' => 'BoxesTestEvents'));
        $this->_eventManager->dispatch('boxes_dbactions_display_text', ['mp_text' => $textDisplay]);

        echo $textDisplay->getText();
        exit;
    }
}
