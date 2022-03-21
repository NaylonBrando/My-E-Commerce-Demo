<?php

namespace src\repository;

use Doctrine\ORM\EntityRepository;
use src\entity\ProductImage;

class ProductImageRepository extends EntityRepository
{
    public function findByProductId(int $productId)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
        $query = $queryBuilder->select('i')
            ->from(ProductImage::class, 'i')
            ->where($queryBuilder->expr()->eq('i.productId', ':productId'))
            ->setParameter(':productId', $productId);
        return $query->getQuery()->getResult();
    }


}