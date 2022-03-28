<?php

namespace src\repository;

use Doctrine\ORM\EntityRepository;


class UserRepository extends EntityRepository
{

    public function findOneByEmail($email)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
        $query = $queryBuilder->select('u')
            ->from(\src\entity\User::class, 'u')
            ->where($queryBuilder->expr()->eq('u.username', ':username'))
            ->setParameter(':username', $email);
        return $query->getQuery()->getResult();
    }

}