<?php


//son kullanici router
use controller\UserController;

class Router
{
    public function run()
    {
        $routes = [
            'homepage' => [
                'url' => '/',
                'class' => 'HomepageController',
                'function' => 'show',
                'type' => 'normal',
                'template' => 'homepage.php'
            ],
            'login' => [
                'url' => '/login',
                'class' => 'UserController',
                'function' => 'login',
                'type' => 'normal',
                'template' => 'login.php'
            ],
            'check-login' => [
                'url' => '/check-login',
                'class' => 'UserController',
                'function' => 'loginCheck',
                'type' => 'check'
            ],
            'logout' => [
                'url' => '/logout',
                'class' => 'UserController',
                'function' => 'logout',
                'type' => 'check'
            ],
            'register' => [
                'url' => '/register',
                'class' => 'UserController',
                'function' => 'register',
                'type' => 'normal',
                'template' => 'register.php'
            ],
            'check-register' => [
                'url' => '/check-register',
                'class' => 'UserController',
                'function' => 'registerCheck',
                'type' => 'check'
            ],
            'profile' => [
                'url' => '/profile',
                'class' => 'UserController',
                'function' => 'profile',
                'type' => 'normal',
                'template' => 'profile.php'
            ],
            'check-update-profile' => [
                'url' => '/check-update-profile',
                'class' => 'UserController',
                'function' => 'update',
                'type' => 'check'
            ],
            'check-update-password' => [
                'url' => '/check-update-password',
                'class' => 'UserController',
                'function' => 'updatePassword',
                'type' => 'check'
            ],
            'shoppingCart' => [
                'url' => '/cart',
                'class' => 'CartController',
                'function' => 'show',
                'type' => 'normal',
                'template' => 'cart.php',
            ],
            'check-add-product-to-cart' => [
                'url' => '/check-add-product-to-cart',
                'class' => 'CartController',
                'function' => 'add',
                'type' => 'check',
            ],
            'check-delete-product-from-cart' => [
                'url' => '/check-delete-product-from-cart',
                'class' => 'CartController',
                'function' => 'delete',
                'type' => 'check',
            ],
            'check-change-quantity-from-cart' => [
                'url' => '/check-change-quantity-from-cart',
                'class' => 'CartController',
                'function' => 'update',
                'type' => 'check',
            ],
            'categoryFilter' => [
                'url' => '/category/{categoryName}',
                'categoryName' => 'categoryName',
                'class' => 'ProductController',
                'function' => 'showProductCardPageWithCategoryFilter',
                'type' => 'normal',
                'template' => 'productCard.php'
            ],
            'categoryFilter-with-parameters' => [
                'url' => "/category/{categoryName}\\?{parameters}",
                'categoryName' => 'categoryName',
                'hasParameters' => true,
                'parameterNames' => ['pg', 'rate', 'price'],
                'class' => 'ProductController',
                'function' => 'showProductCardPageWithCategoryFilter',
                'type' => 'normal',
                'template' => 'productCard.php'
            ],
            'check-add-review-to-product' => [
                'url' => '/check-add-review-to-product',
                'class' => 'ReviewController',
                'function' => 'add',
                'type' => 'check',
            ],
            'product-search' => [
                'url' => '/search/{searchValue}',
                'searchTerm' => 'searchTerm',
                'class' => 'ProductController',
                'function' => 'showProductCardPageWithSearchTerm',
                'type' => 'normal',
                'template' => 'productCard.php'
            ],
            'product-search-with-parameters' => [
                'url' => "/search/{searchValue}\\?{parameters}",
                'searchTerm' => 'searchTerm',
                'hasParameters' => true,
                'parameterNames' => ['pg', 'rate', 'price'],
                'class' => 'ProductController',
                'function' => 'showProductCardPageWithSearchTerm',
                'type' => 'normal',
                'template' => 'productCard.php'
            ],
            'product-slug' => [
                'url' => '/product/{productSlug}',
                'class' => 'ProductController',
                'function' => 'showProductPage',
                'type' => 'normal',
                'template' => 'product.php',
            ],
        ];

        $match = false;
        $request_uri = self::parseUrl();

        if (isset($_SESSION['user_id'])) {
            $userController = new UserController();
            $user = $userController->getById($_SESSION['user_id']);
            $_SESSION['user_status'] = $user->getIsActive();
        }


        if (!isset($_SESSION['user_id']) || $_SESSION['user_status'] == true) {
            foreach ($routes as $router) {

                $patterns = [
                    '{url}' => '([0-9a-zA-Z]+)',
                    '{id}' => '([0-9]+)',
                    '{productSlug}' => '([a-z0-9-&]+)',
                    '{categoryName}' => '([a-z0-9-&]+)',
                    '{searchValue}' => '([a-z0-9-&%]+)',
                    '{parameters}' => '([a-zA-Z0-9=&]+)',
                ];

                $url = str_replace(array_keys($patterns), array_values($patterns), $router['url']);

                if (preg_match('@^' . $url . '$@', $request_uri, $parameters)) {
                    unset($parameters[0]);
                    if (isset($router['categoryName'])) {
                        $parameters[$router['categoryName']] = $parameters[1];
                        unset($parameters[1]);
                    }
                    if (isset($router['searchTerm'])) {
                        $parameters[$router['searchTerm']] = $parameters[1];
                        unset($parameters[1]);
                    }
                    if (isset($router['hasParameters'])) {
                        $params = explode('&', $parameters[2]);
                        foreach ($params as $param) {
                            $param = explode('=', $param);
                            if (in_array($param[0], $router['parameterNames'])) {
                                $parameters[$param[0]] = $param[1];
                            }
                        }
                        unset($parameters[2]);
                    }
                    $controllerClassName = $router['class'];
                    $controllerFile = __DIR__ . '/controller/' . $controllerClassName . '.php';
                    $functionName = $router['function'];
                    $routeType = $router['type'];

                    if ($routeType == 'normal') {
                        $templateFile = $router['template'];
                        $templateFilePath = __DIR__ . '/view/' . $templateFile;
                        if (!file_exists($templateFilePath)) {
                            $pageNotFoundPath = __DIR__ . '/view/404.php';
                            require_once($pageNotFoundPath);
                        }
                        require_once($controllerFile);
                        $match = true;
                        $controllerClassName = '\controller\\' . $controllerClassName;
                        call_user_func_array([new $controllerClassName(), $functionName], [$templateFilePath, $parameters]);
                        break;

                    } elseif ($routeType == 'check') {
                        $match = true;
                        $controllerClassName = '\controller\\' . $controllerClassName;
                        call_user_func_array([new $controllerClassName(), $functionName], $parameters);
                        break;
                    }
                }
            }
            if ($match == false) {
                $pageNotFoundPath = __DIR__ . '/view/404.php';
                require_once($pageNotFoundPath);
            }
        } else {
            $userController = new UserController();
            $userController->logout();
            $_SESSION['login_error'] = 'Your Account Has Been Suspended';
            header('location: /login');
        }
    }

    public static function parseUrl(): array|string
    {
        $dirname = dirname($_SERVER['SCRIPT_NAME']);
        $dirname = $dirname != '/' ? $dirname : null;
        $basename = basename($_SERVER['SCRIPT_NAME']);
        $request_uri = str_replace([$dirname, $basename], null, $_SERVER['REQUEST_URI']);
        if ($request_uri == '') {
            return '/';
        } else {
            return $request_uri;
        }
    }

    public static function parseReferer(): string
    {
        $referer = $_SERVER['HTTP_REFERER'];
        $domainName = $_SERVER['HTTP_ORIGIN'];
        return str_replace($domainName, null, $referer);
    }


}

