<?php

namespace Connection;

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
require_once "vendor/autoload.php";

class Connection
{

    public $entityManager;

    public function __construct()
    {
        $isDevMode = true;
        $proxyDir = null;
        $cache = null;
        $useSimpleAnnotationReader = false;
        $config = Setup::createAnnotationMetadataConfiguration(array(__DIR__."/src"), $isDevMode, $proxyDir, $cache, $useSimpleAnnotationReader);

        $conn = array(
            'driver'   => 'pdo_mysql',
            'user'     => 'root',
            'password' => '',
            'dbname'   => 'ecommerce',
            'host'     => '127.0.0.1'
        );

        $this->entityManager = EntityManager::create($conn, $config);
    }

    /**
     * @return mixed
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }


}