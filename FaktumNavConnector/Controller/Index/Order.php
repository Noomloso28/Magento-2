<?php

declare(strict_types=1);

namespace Immerce\FaktumNavConnector\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Immerce\FaktumNavConnector\Cron\OrderImporterCron;

class Order extends Action
{
    /**
     * @var OrderImporterCron
     */
    private OrderImporterCron $orderImporterCron;

    /**
     * @param Context $context
     */

    public function __construct(
        Context $context,
        OrderImporterCron $orderImporterCron
    ) {

        $this->orderImporterCron = $orderImporterCron;
        parent::__construct($context);
    }

    public function execute()
    {
        // TODO: Implement execute() method.
        $this->orderImporter();
    }

    public function orderImporter()
    {
        $productFileUrl = '';

        return $this->orderImporterCron->execute();

    }
}
