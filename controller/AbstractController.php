<?php

use Doctrine\ORM\EntityManager;

require_once "bootstrap.php";

class AbstractController {

    public $entityManager;

    /**
     * AbstractController constructor.
     */
    public function __construct()
    {
        $connection =  new Connection\Connection();
        $this->entityManager = $connection->entityManager;
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }



}
