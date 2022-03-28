<?php
namespace Router;


//son kullanici router
class Router
{

    public function run()
    {
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
            "check-login" => [
                "url" => "/check-login",
                "class" => "UserController",
                "function" => "loginCheck",
                "type" => "check"
            ],
            "logout" => [
                "url" => "/logout",
                "class" => "UserController",
                "function" => "logout",
                "type" => "check"
            ],
            "register" => [
                "url" => "/register",
                "class" => "UserController",
                "function" => "register",
                "type" => "normal",
                "template" => "register.php"
            ],
            "check-register" => [
                "url" => "/check-register",
                "class" => "UserController",
                "function" => "registerCheck",
                "type" => "check"
            ],
            "profile" => [
                "url" => "/profile",
                "class" => "UserController",
                "function" => "profile",
                "type" => "normal",
                "template" => "profile.php"
            ],
            "check-update-profile" => [
                "url" => "/check-update-profile",
                "class" => "UserController",
                "function" => "update",
                "type" => "check"
            ],
            "check-update-password" => [
                "url" => "/check-update-password",
                "class" => "UserController",
                "function" => "updatePassword",
                "type" => "check"
            ],
            "shoppingCart" => [
                "url" => "/cart",
                "class" => "CartController",
                "function" => "show",
                "type" => "normal",
                "template" => "cart.php",
            ],
            "check-add-product-to-cart" => [
                "url" => "/check-add-product-to-cart",
                "class" => "CartController",
                "function" => "add",
                "type" => "check",
            ],
            "check-delete-product-from-cart" => [
                "url" => "/check-delete-product-from-cart/{id}",
                "class" => "CartController",
                "function" => "delete",
                "type" => "check",
            ],
            "product-slug" => [
                "url" => "/{productSlug}",
                "class" => "ProductController",
                "function" => "show",
                "type" => "normal",
                "template" => "product.php",
            ],
        ];

        $match = false;
        $request_uri = self::parse_url();

        foreach ($routes as $router) {

            $patterns = [
                '{url}' => '([0-9a-zA-Z]+)',
                '{id}' => '([0-9]+)',
                '{productSlug}' => '([a-z0-9-&]+)'
            ];

            $url = str_replace(array_keys($patterns), array_values($patterns), $router['url']);

            if (preg_match('@^' . $url . '$@', $request_uri, $parameters)) {
                unset($parameters[0]);
                $controllerClassName = $router['class'];
                $controllerFile = __DIR__ . '/controller/' . $controllerClassName . '.php';
                $functionName = $router['function'];
                $routeType = $router['type'];

                if ($routeType == "normal") {
                    $templateFile = $router['template'];
                    $templateFilePath = __DIR__ . "/view/" . $templateFile;
                    if (!file_exists($templateFilePath)) {
                        $pageNotFoundPath = __DIR__ . "/view/404.php";
                        require_once($pageNotFoundPath);
                    }
                    require_once($controllerFile);
                    $match = true;
                    $controllerClassName = '\controller\\' . $controllerClassName;
                    call_user_func_array(array(new $controllerClassName, $functionName), array($templateFilePath, $parameters));
                    break;

                } else if ($routeType == "check") {
                    $match = true;
                    $controllerClassName = '\controller\\' . $controllerClassName;
                    call_user_func_array(array(new $controllerClassName, $functionName), $parameters);
                    break;
                }
            }
        }
        if ($match == false) {
            $pageNotFoundPath = __DIR__ . "/view/404.php";
            require_once($pageNotFoundPath);
        }
    }

    public static function parse_url(): array|string
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

