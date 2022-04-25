<?php

namespace src\repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use src\dto\ReviewWithUserDto;
use src\entity\Product;
use src\entity\Review;
use src\entity\User;

class ReviewRepository extends EntityRepository
{
    /**
     * @return ReviewWithUserDto[]
     */
    public function findByProductId(int $productId) : array
    {
        $reviewWithUserDtoArray = [];
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('r', 'u.firstName', 'u.lastName')
            ->from(Review::class, 'r')
            ->innerJoin(
                User::class,
                'u',
                Join::WITH,
                'u.id = r.userId',
            )
            ->where('r.productId = :productId')
            ->andWhere('r.status = 1')
            ->orderBy('r.status', 'DESC')
            ->setParameter('productId', $productId);
        $result = $qb->getQuery()->getResult();
        
        foreach ($result as $row) {
            $reviewWithUserDto = new ReviewWithUserDto();
            $review = $row[0];
            $reviewWithUserDto->setReview($review);
            $reviewWithUserDto->setUserName($row['firstName']);
            $reviewWithUserDto->setUserLastName($row['lastName']);
            $reviewWithUserDtoArray[] = $reviewWithUserDto;
        }
        
        return $reviewWithUserDtoArray;
    }

    /**
     * @return ReviewWithUserDto[]
     */
    public function findByStatus(int $status) : array
    {
        $reviewWithUserDtoArray = [];
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('r', 'u.firstName', 'u.lastName', 'u.email','p.title', 'p.id', 'p.slug')
            ->from(Review::class, 'r')
            ->innerJoin(
                User::class,
                'u',
                Join::WITH,
                'u.id = r.userId',
            )
            ->innerJoin(
                Product::class,
                'p',
                Join::WITH,
                'p.id = r.productId',
            )
            ->where('r.status = :status')
            ->orderBy('r.createdAt', 'DESC')
            ->setParameter('status', $status);
        $result = $qb->getQuery()->getResult();

        foreach ($result as $row) {
            $reviewWithUserDto = new ReviewWithUserDto();
            $review = $row[0];
            $reviewWithUserDto->setReview($review);
            $reviewWithUserDto->setUserName($row['firstName']);
            $reviewWithUserDto->setUserLastName($row['lastName']);
            $reviewWithUserDto->setUserEmail($row['email']);
            $reviewWithUserDto->setProductTitle($row['title']);
            $reviewWithUserDto->setProductId($row['id']);
            $reviewWithUserDto->setProductSlug($row['slug']);
            
            $reviewWithUserDtoArray[] = $reviewWithUserDto;
        }

        return $reviewWithUserDtoArray;
    }
    
    public function deleteReviewsByProductId(int $productId) : void
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->delete(Review::class, 'r')
            ->where('r.productId = :productId')
            ->setParameter('productId', $productId);
        $qb->getQuery()->execute();
    }
}