<?php
session_start();
require_once __DIR__ . "/load.php";

$uri = $_SERVER['REQUEST_URI'];

$routerEntity = new Router();
$routerEntity->run($uri);
?>
