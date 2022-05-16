<?php
function loader($dir)
{
    $cdir = scandir($dir);
    foreach ($cdir as $key => $value) {
        if (str_contains($value, '.php')) {
            $test = $dir . '/' . $value;
            require_once($test);
        }
    }
}

require_once('../Connection.php');
loader('../src/entity');
loader('../src/repository');
loader('../src/dto');
loader('src/controller');
loader(__DIR__ . '/src/helper');
require_once(__DIR__ . '/Router.php');



