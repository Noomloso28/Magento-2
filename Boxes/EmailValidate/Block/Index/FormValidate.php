<?php
namespace Boxes\EmailValidate\Block\Index;

use Magento\Framework\View\Element\Template;

use Magento\Customer\Model\ResourceModel\Customer\CollectionFactory as CustomerCollectionFactory;
use Magento\Customer\Model\Customer;


class FormValidate extends \Magento\Framework\View\Element\Template
{



    private CustomerCollectionFactory $CustomerCollectionFactory;
    private Customer $customer;


    public function __construct(
        Template\Context $context,
        CustomerCollectionFactory $CustomerCollectionFactory,
        Customer $customer

    )
    {

        $this->CustomerCollectionFactory = $CustomerCollectionFactory;
        $this->customer = $customer;


        parent::__construct($context);
    }

    public function getEmailDetails()
    {

        ####################### Get All User Details ##################

        $CustomerCollectionFactory = $this->CustomerCollectionFactory->create();
        $excludeCustomerId = array();

        foreach ($CustomerCollectionFactory as $customerDetails ){

            $customer_details = $this->customer->load( $customerDetails->getId()  );
            $excludeCustomerId[] = $customer_details->getEmail();

        }

/*
        echo '<pre>';
        var_dump( $excludeCustomerId );
        echo '</pre>';
*/

        return $excludeCustomerId;


    }
}
