<?php

$routes = [
    "homepage" => [
        "url" => "/",
        "class" => "HomepageController",
        "function" => "index",
        "type" => "normal",
        "template" => "homepage.php"
    ],
    "login" => [
        "url" => "/login",
        "class" => "UserController",
        "function" => "login",
        "type" => "normal",
        "template" => "login.php"
    ],
    "check-login"=>[
        "url" => "/check-login",
        "class" => "UserController",
        "function" => "loginCheck",
        "type" => "check"
    ],
    "logout"=>[
        "url" => "/logout",
        "class" => "UserController",
        "function" => "logout",
        "type" => "check"
    ],
    "register"=>[
        "url" => "/register",
        "class" => "UserController",
        "function" => "register",
        "type" => "normal",
        "template" => "register.php"
    ],
    "check-register"=>[
        "url" => "/check-register",
        "class" => "UserController",
        "function" => "registerCheck",
        "type" => "check"
    ],
    "product-list"=>[
        "url" => "/product-list",
        "class" => "ProductController",
        "function" => "listProducts",
        "type" => "normal",
        "template" =>"product.php"
    ],
    "category"=>[
        "url" => "/category",
        "class" => "CategoryController",
        "function" => "show",
        "type" => "normal",
        "template" =>"category.php"
    ],
    "add-category"=>[
        "url" => "/add-category",
        "class" => "CategoryController",
        "function" => "add",
        "type" => "check",
    ],
    "delete-category"=>[
        "url" => "/delete-category",
        "class" => "CategoryController",
        "function" => "delete",
        "type" => "check",
    ],
    "update-category"=>[
        "url" => "/update-category/{id}",
        "class" => "CategoryController",
        "function" => "updateShow",
        "type" => "normal",
        "template" =>"updateCategory.php"
    ],
    "update-category-check"=>[
        "url" => "/update-category-check",
        "class" => "CategoryController",
        "function" => "updateCheck",
        "type" => "check",
    ],


];


//admin router
class Router
{


    public function run()
    {
        global $routes;

        $request_uri = self::parse_url();

        foreach ($routes as $router) {

            $patterns = [
                '{url}' => '([0-9a-zA-Z]+)',
                '{id}' => '([0-9]+)'
            ];

            $url = str_replace(array_keys($patterns), array_values($patterns), $router['url']);

            if (preg_match('@^' . $url . '$@', $request_uri, $parameters)) {
                unset($parameters[0]);
                $controllerClassName = $router['class'];
                $controllerFile = __DIR__ . '/controller/' . $controllerClassName . '.php';
                $functionName=$router['function'];
                $routeType=$router['type'];
                //call_user_func_array(new $controllerFile, $route['function'], $parameters);

                try {
                    if ($routeType == "normal") {
                        $templateFile = $router['template'];
                        $templateFilePath = __DIR__ . "/view/" . $templateFile;
                        if (!file_exists($templateFilePath)) {
                            echo "404";
                            exit;
                        }
                        require_once($controllerFile);
                        call_user_func_array(array(new $controllerClassName, $functionName), array($templateFilePath));
                        break;

                    } else if ($routeType == "check") {
                        call_user_func_array(array(new $controllerClassName, $functionName), $parameters);
                        break;
                    }
                } catch (Exception $exception) {
                    var_dump($exception->getMessage());
                    exit;
                }

            }
        }
    }

    public static function parse_url()
    {
        $dirname = dirname($_SERVER['SCRIPT_NAME']);
        $dirname = $dirname != '/' ? $dirname : null;
        $basename = basename($_SERVER['SCRIPT_NAME']);
        $request_uri = str_replace([$dirname, $basename], null, $_SERVER['REQUEST_URI']);
        if ($request_uri == "") {
            return "/";
        } else {
            return $request_uri;
        }
    }
}

