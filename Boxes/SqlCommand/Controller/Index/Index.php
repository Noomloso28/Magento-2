<?php
namespace Boxes\SqlCommand\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Boxes\SqlCommand\Model\DbRelation;

class Index extends Action{

    /**
     * @var DbRelation
     */
    private DbRelation $GetDataDbRelation;


    public function __construct(
        Context $context,
        DbRelation $dbRelation
    )
    {
        $this->GetDataDbRelation = $dbRelation;

        parent::__construct($context);
    }

    public function execute()
    {
        // TODO: Implement execute() method.
        echo '<pre>';
        print_r( $this->getDataFromDatabase() );
        echo '</pre>';
    }

    public function getDataFromDatabase()
    {
        if($this->GetDataDbRelation){

            return $this->GetDataDbRelation->getCustomerData();
        }
    }

}
