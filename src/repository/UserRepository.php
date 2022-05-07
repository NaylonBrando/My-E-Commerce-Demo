<?php

namespace src\repository;

use Doctrine\ORM\EntityRepository;
use src\entity\User;


class UserRepository extends EntityRepository
{

    /**
     * @return User[]
     */
    public function findUsersWithLimit($pageNumber, int $limit): array
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('u')
            ->from(User::class, 'u')
            ->setFirstResult(($pageNumber - 1) * $limit)
            ->setMaxResults($limit);
        return  $qb->getQuery()->getResult();
    }

    public function countUsers(bool $isActive = null): int
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('COUNT(u.id)')
            ->from(User::class, 'u');
        if ($isActive !== null) {
            if ($isActive) {
                $qb->where('u.isActive = 1');
            } else {
                $qb->where('u.isActive = 0');
            }
        }
        return $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * @return User[]
     */
    public function findUsersBySearchTerm(string $searchTerm, int $pageNumber, int $limit): array{
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('u')
            ->from(User::class, 'u')
            ->where('u.firstName LIKE :searchTerm')
            ->orWhere('u.lastName LIKE :searchTerm')
            ->setParameter('searchTerm', '%'.$searchTerm.'%')
            ->setFirstResult(($pageNumber - 1) * $limit)
            ->setMaxResults($limit);
        return  $qb->getQuery()->getResult();
    }
}