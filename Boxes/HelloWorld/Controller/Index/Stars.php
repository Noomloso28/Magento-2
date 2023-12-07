<?php
namespace Boxes\HelloWorld\Controller\Index;

use Boxes\HelloWorld\Model\Products\ProductReviews;

class Stars extends \Magento\Framework\App\Action\Action
{

    private ProductReviews $productReviews;


    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        ProductReviews $productReviews

    )
    {
        $this->productReviews = $productReviews;
        return parent::__construct($context);
    }

    public function execute( )
    {

        echo '<pre>';
            echo '<h1>User Reviews Product</h1>';
            var_dump( $this->getReviewUsers());
            echo '<h1>User Star Product</h1>';
            var_dump( $this->getStarsFromUserReview() );
        echo '</pre>';

    }

    protected function getReviewUsers(){

        return $this->productReviews->getReviewCollection( 2003 );
    }

    private function getStarsFromUserReview(){

        return $this->productReviews->getRatingCollection( 2003 );
    }

}
