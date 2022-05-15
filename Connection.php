<?php

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;

require_once 'vendor/autoload.php';

class Connection
{
    public EntityManager $entityManager;

    public function __construct()
    {
        $isDevMode = true;
        $proxyDir = null;
        $cache = null;
        $useSimpleAnnotationReader = false;
        $config = Setup::createAnnotationMetadataConfiguration([__DIR__ . '/src'], $isDevMode, $proxyDir, $cache, $useSimpleAnnotationReader);

        $conn = [
            'driver' => 'pdo_mysql',
            'user' => 'root',
            'password' => '',
            'dbname' => 'ecommerce',
            'host' => '127.0.0.1'
        ];

        $this->entityManager = EntityManager::create($conn, $config);
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager(): EntityManager
    {
        return $this->entityManager;
    }
}