<?php
// cli-config.php
use Doctrine\ORM\Tools\Console\ConsoleRunner;

require_once 'Connection.php';
$entityManager = new \Connection\Connection();

return ConsoleRunner::createHelperSet($entityManager->entityManager);