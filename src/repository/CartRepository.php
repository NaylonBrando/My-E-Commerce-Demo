<?php

namespace src\repository;

use Doctrine\ORM\EntityRepository;
use src\entity\Cart;

class CartRepository extends EntityRepository
{
    public function deleteByProductId($productId)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->delete(Cart::class, 'c')
            ->where('c.productId = :productId')
            ->setParameter('productId', $productId);

        return $qb->getQuery()->getResult();
    }
}