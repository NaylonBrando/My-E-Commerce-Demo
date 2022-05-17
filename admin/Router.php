<?php

namespace admin;

//admin router
class Router
{
    public static function parseReferer(): string
    {
        $referer = $_SERVER['HTTP_REFERER'];
        $domainName = $_SERVER['HTTP_ORIGIN'];
        return str_replace($domainName, null, $referer);
    }

    public function run()
    {
        $routes = [
            'homepage' => [
                'url' => '/',
                'class' => 'HomepageController',
                'function' => 'index',
                'type' => 'normal',
                'template' => 'adminPanelTemplate.php'

            ],
            'login' => [
                'url' => '/login',
                'class' => 'AdminController',
                'function' => 'login',
                'type' => 'normal',
                'template' => 'login.php'
            ],
            'logout' => [
                'url' => '/logout',
                'class' => 'AdminController',
                'function' => 'logout',
                'type' => 'check'
            ],
            'check-login' => [
                'url' => '/check-login',
                'class' => 'AdminController',
                'function' => 'loginCheck',
                'type' => 'check'
            ],
            'category' => [
                'url' => '/category',
                'class' => 'CategoryController',
                'function' => 'show',
                'type' => 'normal',
                'template' => 'category.php'
            ],
            'add-category' => [
                'url' => '/add-category',
                'class' => 'CategoryController',
                'function' => 'add',
                'type' => 'check',
            ],
            'check-delete-category' => [
                'url' => '/check-delete-category/{id}',
                'class' => 'CategoryController',
                'function' => 'delete',
                'type' => 'check',
            ],
            'update-category' => [
                'url' => '/category/update/{id}',
                'class' => 'CategoryController',
                'function' => 'showUpdate',
                'type' => 'normal',
                'template' => 'updateCategory.php'
            ],
            'check-update-category' => [
                'url' => '/check-update-category',
                'class' => 'CategoryController',
                'function' => 'update',
                'type' => 'check',
            ],
            'brand' => [
                'url' => '/brand',
                'class' => 'BrandController',
                'function' => 'show',
                'type' => 'normal',
                'template' => 'brand.php'
            ],
            'add-brand' => [
                'url' => '/brand/add',
                'class' => 'BrandController',
                'function' => 'showAdd',
                'type' => 'normal',
                'template' => 'addBrand.php'
            ],
            'check-add-brand' => [
                'url' => '/check-add-brand',
                'class' => 'BrandController',
                'function' => 'add',
                'type' => 'check',
            ],
            'update-brand' => [
                'url' => '/brand/update/{id}',
                'class' => 'BrandController',
                'function' => 'showUpdate',
                'type' => 'normal',
                'template' => 'updateBrand.php'
            ],
            'check-update-brand' => [
                'url' => '/check-update-brand',
                'class' => 'BrandController',
                'function' => 'update',
                'type' => 'check',
            ],
            'check-delete-brand' => [
                'url' => '/check-delete-brand/{id}',
                'class' => 'BrandController',
                'function' => 'delete',
                'type' => 'check',
            ],
            'product' => [
                'url' => '/product',
                'class' => 'ProductController',
                'function' => 'show',
                'type' => 'normal',
                'template' => 'product.php'
            ],
            'product-with-pagination' => [
                'url' => "/product\\?{parameters}",
                'hasParameters' => true,
                'parameterNames' => ['pg'],
                'class' => 'ProductController',
                'function' => 'show',
                'type' => 'normal',
                'template' => 'product.php'
            ],
            'add-product' => [
                'url' => '/product/add',
                'class' => 'ProductController',
                'function' => 'showAdd',
                'type' => 'normal',
                'template' => 'addProduct.php'
            ],
            'check-add-product' => [
                'url' => '/check-add-product',
                'class' => 'ProductController',
                'function' => 'add',
                'type' => 'check',
            ],
            'product-image' => [
                'url' => '/product/image/{id}',
                'class' => 'ProductImageController',
                'function' => 'show',
                'type' => 'normal',
                'template' => 'productImage.php'
            ],
            'check-delete-product-image' => [
                'url' => '/check-delete-product-image/{id}',
                'class' => 'ProductImageController',
                'function' => 'delete',
                'type' => 'check',
            ],
            'check-add-product-image' => [
                'url' => '/check-add-product-image/{id}',
                'class' => 'ProductImageController',
                'function' => 'add',
                'type' => 'check',
            ],
            'check-set-thumbnail-product-image' => [
                'url' => '/check-set-thumbnail-product-image/{id}',
                'class' => 'ProductImageController',
                'function' => 'setThumbnail',
                'type' => 'check',
            ],
            'check-set-active-product' => [
                'url' => '/check-set-active-product/{id}',
                'class' => 'ProductController',
                'function' => 'setIsActiveTrue',
                'type' => 'check',
            ],
            'check-set-deactivate-product' => [
                'url' => '/check-set-deactivate-product/{id}',
                'class' => 'ProductController',
                'function' => 'setIsActiveFalse',
                'type' => 'check',
            ],
            'check-delete-product' => [
                'url' => '/check-delete-product/{id}',
                'class' => 'ProductController',
                'function' => 'delete',
                'type' => 'check',
            ],
            'update-product' => [
                'url' => '/product/update/{id}',
                'class' => 'ProductController',
                'function' => 'showUpdate',
                'type' => 'normal',
                'template' => 'updateProduct.php'
            ],
            'check-update-product' => [
                'url' => '/check-update-product',
                'class' => 'ProductController',
                'function' => 'update',
                'type' => 'check',
            ],
            'review' => [
                'url' => '/review',
                'class' => 'ReviewController',
                'function' => 'show',
                'type' => 'normal',
                'template' => 'review.php'
            ],
            'check-approve-single-review' => [
                'url' => '/check-approve-single-review/{id}',
                'class' => 'ReviewController',
                'function' => 'approveReview',
                'type' => 'check',
            ],
            'check-delete-single-review' => [
                'url' => '/check-delete-single-review/{id}',
                'class' => 'ReviewController',
                'function' => 'delete',
                'type' => 'check',
            ],
            'check-approve-selected-reviews' => [
                'url' => '/check-approve-selected-reviews',
                'class' => 'ReviewController',
                'function' => 'approveSelectedReviews',
                'type' => 'check',
            ],
            'check-delete-selected-reviews' => [
                'url' => '/check-delete-selected-reviews',
                'class' => 'ReviewController',
                'function' => 'deleteSelectedReviews',
                'type' => 'check',
            ],
            'user' => [
                'url' => '/user',
                'class' => 'UserController',
                'function' => 'show',
                'type' => 'normal',
                'template' => 'user.php'
            ],
            'user-with-pagination' => [
                'url' => "/user\\?{parameters}",
                'hasParameters' => true,
                'parameterNames' => ['pg'],
                'class' => 'UserController',
                'function' => 'show',
                'type' => 'normal',
                'template' => 'user.php'
            ],
            'user-search' => [
                'url' => '/user/search/{searchValue}',
                'searchTerm' => 'searchTerm',
                'class' => 'UserController',
                'function' => 'showUserSearch',
                'type' => 'normal',
                'template' => 'user.php'
            ],
            'user-search-with-parameters' => [
                'url' => "/user/search/{searchValue}\\?{parameters}",
                'searchTerm' => 'searchTerm',
                'hasParameters' => true,
                'parameterNames' => ['pg'],
                'class' => 'UserController',
                'function' => 'showUserSearch',
                'type' => 'normal',
                'template' => 'user.php'
            ],
            'check-change-user-status' => [
                'url' => '/check-change-user-status/{id}',
                'class' => 'UserController',
                'function' => 'changeStatus',
                'type' => 'check',
            ],
            'check-delete-user' => [
                'url' => '/check-delete-user/{id}',
                'class' => 'UserController',
                'function' => 'delete',
                'type' => 'check',
            ],
            'update-user' => [
                'url' => '/user/update/{id}',
                'class' => 'UserController',
                'function' => 'showUpdate',
                'type' => 'normal',
                'template' => 'updateUser.php'
            ],
            'check-update-user' => [
                'url' => '/check-update-user',
                'class' => 'UserController',
                'function' => 'update',
                'type' => 'check',
            ],
            'product-search' => [
                'url' => '/product/search/{searchValue}',
                'searchTerm' => 'searchTerm',
                'class' => 'ProductController',
                'function' => 'showProductSearch',
                'type' => 'normal',
                'template' => 'product.php'
            ],
            'product-search-with-parameters' => [
                'url' => "/product/search/{searchValue}\\?{parameters}",
                'searchTerm' => 'searchTerm',
                'hasParameters' => true,
                'parameterNames' => ['pg'],
                'class' => 'ProductController',
                'function' => 'showProductSearch',
                'type' => 'normal',
                'template' => 'product.php'
            ],
        ];

        $match = false;

        $requestUrl = static::parseUrl();

        if ((!isset($_SESSION['admin_id']) && ((strcmp($requestUrl, '/login') == 0) || strcmp($requestUrl, '/check-login') == 0))
            || isset($_SESSION['admin_id'])) {
            foreach ($routes as $router) {
                $patterns = [
                    '{url}' => '([0-9a-zA-Z]+)',
                    '{id}' => '([0-9]+)',
                    '{searchValue}' => '([a-z0-9-&%]+)',
                    '{parameters}' => '([a-zA-Z0-9=&]+)',
                ];

                $url = str_replace(array_keys($patterns), array_values($patterns), $router['url']);

                if (preg_match('@^' . $url . '$@', $requestUrl, $parameters)) {
                    unset($parameters[0]);
                    if (isset($router['searchTerm'])) {
                        $parameters[$router['searchTerm']] = $parameters[1];
                        unset($parameters[1]);
                    }
                    if (isset($router['hasParameters'])) {
                        if (isset($parameters[2])) {
                            $params = explode('&', $parameters[2]);
                            unset($parameters[2]);
                        } else {
                            $params = explode('&', $parameters[1]);
                        }
                        foreach ($params as $param) {
                            $param = explode('=', $param);
                            if (in_array($param[0], $router['parameterNames'])) {
                                $parameters[$param[0]] = $param[1];
                            }
                            unset($parameters[1]);
                        }

                    }
                    $controllerClassName = $router['class'];
                    $controllerFile = __DIR__ . '\src\controller\\' . $controllerClassName . '.php';
                    $functionName = $router['function'];
                    $routeType = $router['type'];

                    if ($routeType == 'normal') {
                        $templateFile = $router['template'];
                        $templateFilePath = __DIR__ . '\src\view\\' . $templateFile;
                        if (!file_exists($templateFilePath)) {
                            $pageNotFoundPath = __DIR__ . '\src\view\\404.php';
                            require_once($pageNotFoundPath);
                        }
                        require_once($controllerFile);
                        $controllerClassName = 'admin\src\controller\\' . $controllerClassName;
                        call_user_func_array([new $controllerClassName(), $functionName], [$templateFilePath, $parameters]);
                        $match = true;
                        break;

                    } elseif ($routeType == 'check') {
                        $controllerClassName = 'admin\controller\\' . $controllerClassName;
                        call_user_func_array([new $controllerClassName(), $functionName], $parameters);
                        $match = true;
                        break;
                    }

                }
            }
            if ($match == false) {
                $pageNotFoundPath = __DIR__ . '/view/404.php';
                require_once($pageNotFoundPath);
            }
        } else {
            header('location: /admin/login');
        }

    }

    public static function parseUrl(): string
    {
        $dirname = dirname($_SERVER['SCRIPT_NAME']);
        $dirname = $dirname != '/' ? $dirname : null;
        $basename = basename($_SERVER['SCRIPT_NAME']);
        $requestUrl = str_replace([$dirname, $basename], null, $_SERVER['REQUEST_URI']);
        if ($requestUrl == '') {
            return '/';
        } else {
            return $requestUrl;
        }
    }
}

