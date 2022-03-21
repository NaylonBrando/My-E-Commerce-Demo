<?php

use Doctrine\ORM\EntityManager;

require_once "bootstrap.php";

class AdminAbstractController
{

    public EntityManager $entityManager;

    /**
     * AbstractController constructor.
     */
    public function __construct()
    {
        $connection = new Connection\Connection();
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
