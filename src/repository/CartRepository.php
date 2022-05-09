<?php

namespace src\repository;

use Doctrine\ORM\EntityRepository;
use src\entity\Cart;
use src\entity\CartItem;

class CartRepository extends EntityRepository
{


    public function findCartByUserId(int $userId): ?Cart
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('c')
            ->from(Cart::class, 'c')
            ->innerJoin('c.user', 'u')
            ->where('u.id = :userId')
            ->setParameter('userId', $userId);
        return $qb->getQuery()->getOneOrNullResult();
    }


    public function findCartItemByCartIdandProductId(int $cartId, int $productId): ?CartItem
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('ctm')
            ->from(CartItem::class, 'ctm')
            ->innerJoin('ctm.cart', 'c')
            ->innerJoin('ctm.product', 'p')
            ->where('c.id = :cartId')
            ->andWhere('p.id = :productId')
            ->setParameter('cartId', $cartId)
            ->setParameter('productId', $productId);
        return $qb->getQuery()->getOneOrNullResult();
    }


    /**
     * @param int $userId
     * @return CartItem[]
     */
    public function findCartItemsByCartId(int $cartId): array
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('ctm')
            ->from(CartItem::class, 'ctm')
            ->innerJoin('ctm.cart', 'c')
            ->where('c.id = :cartId')
            ->setParameter('cartId', $cartId);
        return $qb->getQuery()->getResult();
    }


}