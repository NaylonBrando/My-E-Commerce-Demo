<?php
function loader($dir)
{
    $cdir = scandir($dir);
    foreach ($cdir as $key => $value) {
        if (str_contains($value, '.php')) {
            $test = __DIR__ . '/' . $dir . '/' . $value;
            require_once($test);
        }
    }
}

require_once('Connection.php');
loader('src/entity');
loader('src/repository');
loader('src/dto');
loader('controller');
require_once('Router.php');
