<?php
session_start();
require_once __DIR__ . "/load.php";

$routerEntity = new Router();
$routerEntity->run();

