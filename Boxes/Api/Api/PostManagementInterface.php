<?php
namespace Boxes\Api\Api;


interface PostManagementInterface {


    /**
     * GET for Post api
     * @param string $cartId The shopping cart ID.
     * @return string
     *
     * @param int $itemId The item ID.
     * @return Int
     */

    public function getPost($cartId , $itemId );

}
