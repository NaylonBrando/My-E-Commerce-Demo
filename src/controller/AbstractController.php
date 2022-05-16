<?php

namespace src\controller;

use Connection;
use Doctrine\ORM\EntityManager;

require_once($_SERVER['DOCUMENT_ROOT'] . '/bootstrap.php');

class AbstractController
{
    public EntityManager $entityManager;

    /**
     * AbstractController constructor.
     */
    public function __construct()
    {
        $connection = new Connection();
        $this->entityManager = $connection->entityManager;
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager(): EntityManager
    {
        return $this->entityManager;
    }
}