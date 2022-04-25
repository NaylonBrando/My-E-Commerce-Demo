<?php
use Router\Router;

require_once __DIR__ . "/load.php";
session_start();


$routerEntity = new Router();
$routerEntity->run();

