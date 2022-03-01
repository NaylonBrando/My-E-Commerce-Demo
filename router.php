<?php
use Doctrine\ORM\EntityManager;
require_once('dbcon.php');
require_once ('Connection.php');
require_once('src/entity/Route.php');

class Router
{
    private EntityManager $entityManager;

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
    public function getEntityManager(): EntityManager
    {
        return $this->entityManager;
    }

    public function run($url)
    {
        /** @var src\entity\Routes $routeResult */
        $routeResult = $this->entityManager->getRepository(src\entity\Routes::class)->findAll();

        //print_r($routeResult);

        /** @var src\entity\Routes $router */
        foreach ($routeResult as $router) {

            $routerSplitArray = explode("/", $router->getUrl());
            $routerCount = count($routerSplitArray);

            $urlSplitArray = explode("/", $url);
            unset($urlSplitArray[0]);
            $urlCount = count($urlSplitArray);

            $urlAndRouterCompare=strcmp(strtolower($urlSplitArray[1]),$routerSplitArray[0]);

            if (($urlCount == $routerCount && $urlAndRouterCompare == 0) || $router->getUrl()=="^/$") {
                $routerSlashed = str_replace("/", "\/", $router->getUrl());
                $result = preg_match("/" . $routerSlashed ."/",  $url , $matches);
                if ($result == 1) {
                    unset($matches[0]);
                    if (count($matches) > 0) {
                        sort($matches);
                    }
                    try {
                        $controllerClassName = $router->getClass();
                        $controllerClass = new $controllerClassName;
                        $functionName = $router->getFunction();

                        if ($router->getType() == "normal") {
                            $templateFile = $router->getTemplate();
                            $templateFilePath = __DIR__ . "/view/" . $templateFile;
                            if (!file_exists($templateFilePath)) {
                                echo "404";
                                exit;
                            }
                            call_user_func_array(array($controllerClass, $functionName), array($templateFilePath, $matches));
                            break;

                        }
                        else if ($router->getType() == "check") {
                            call_user_func_array(array($controllerClass, $functionName), $matches);
                            break;
                        }
                    } catch (Exception $exception) {
                        var_dump($exception->getMessage());
                        exit;
                    }
                }
            }
        }
    }
}

