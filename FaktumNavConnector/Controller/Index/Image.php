<?php

declare(strict_types=1);

namespace Immerce\FaktumNavConnector\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Immerce\FaktumNavConnector\Model\ProductImageGenerator;

class Image extends Action
{

    /**
     * @var ProductImageGenerator
     */
    private ProductImageGenerator $productImageGenerator;

    /**
     * @param Context $context
     */

    public function __construct(
        Context $context,
        ProductImageGenerator $productImageGenerator
    ) {

        $this->productImageGenerator = $productImageGenerator;
        parent::__construct($context);
    }

    public function execute()
    {
        // TODO: Implement execute() method.
        $this->productImageGenerator->execute();
    }

}
