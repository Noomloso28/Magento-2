<?php
namespace Boxes\Api\Model;


class PostManagement {

    /**
     * {@inheritdoc}
     */
    public function getPost($cartId , $itemId)
    {
        //$post_id = $this->getPostId( $id );

        return  'api GET return the $param ' . $cartId . $itemId;
    }

    public function getPostId( int  $id )
    {
        return 'api GET Post Id : ' . $id;
    }

}
