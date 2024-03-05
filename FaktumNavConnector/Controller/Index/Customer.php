<?php

declare(strict_types=1);

namespace Immerce\FaktumNavConnector\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Immerce\FaktumNavConnector\Cron\CustomerImporterCron;

class Customer extends Action
{
    /**
     * @var CustomerImporterCron
     */
    private CustomerImporterCron $customerImporterCron;

    /**
     * @param Context $context
     */

    public function __construct(
        Context $context,
        CustomerImporterCron $customerImporterCron
    ) {

        $this->customerImporterCron = $customerImporterCron;
        parent::__construct($context);
    }

    public function execute()
    {
        // TODO: Implement execute() method.

        $this->customerImporter();
    }

    public function customerImporter()
    {
        $productFileUrl = '';

        return $this->customerImporterCron->execute();

    }
}
