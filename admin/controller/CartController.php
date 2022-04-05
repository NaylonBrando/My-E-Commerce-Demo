<?php

namespace admin\controller;

use src\entity\Cart;

class CartController extends AdminAbstractController
{
    public function deleteByProductId($productId)
    {
        $em = $this->getEntityManager()->getRepository(Cart::class);
        $em->deleteByProductId($productId);

    }

}