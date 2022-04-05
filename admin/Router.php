<?php

namespace admin;


//admin router
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
                "template" => "adminPanelTemplate.php"

            ],
            "login" => [
                "url" => "/login",
                "class" => "AdminController",
                "function" => "login",
                "type" => "normal",
                "template" => "login.php"
            ],
            "logout" => [
                "url" => "/logout",
                "class" => "AdminController",
                "function" => "logout",
                "type" => "check"
            ],
            "check-login" => [
                "url" => "/check-login",
                "class" => "AdminController",
                "function" => "loginCheck",
                "type" => "check"
            ],
            "category" => [
                "url" => "/category",
                "class" => "CategoryController",
                "function" => "show",
                "type" => "normal",
                "template" => "category.php"
            ],
            "add-category" => [
                "url" => "/add-category",
                "class" => "CategoryController",
                "function" => "add",
                "type" => "check",
            ],
            "check-delete-category" => [
                "url" => "/check-delete-category/{id}",
                "class" => "CategoryController",
                "function" => "delete",
                "type" => "check",
            ],
            "update-category" => [
                "url" => "/category/update/{id}",
                "class" => "CategoryController",
                "function" => "showUpdate",
                "type" => "normal",
                "template" => "updateCategory.php"
            ],
            "check-update-category" => [
                "url" => "/check-update-category",
                "class" => "CategoryController",
                "function" => "update",
                "type" => "check",
            ],
            "brand" => [
                "url" => "/brand",
                "class" => "BrandController",
                "function" => "show",
                "type" => "normal",
                "template" => "brand.php"
            ],
            "add-brand" => [
                "url" => "/brand/add",
                "class" => "BrandController",
                "function" => "showAdd",
                "type" => "normal",
                "template" => "addBrand.php"
            ],
            "check-add-brand" => [
                "url" => "/check-add-brand",
                "class" => "BrandController",
                "function" => "add",
                "type" => "check",
            ],
            "update-brand" => [
                "url" => "/brand/update/{id}",
                "class" => "BrandController",
                "function" => "showUpdate",
                "type" => "normal",
                "template" => "updateBrand.php"
            ],
            "check-update-brand" => [
                "url" => "/check-update-brand",
                "class" => "BrandController",
                "function" => "update",
                "type" => "check",
            ],
            "check-delete-brand" => [
                "url" => "/check-delete-brand/{id}",
                "class" => "BrandController",
                "function" => "delete",
                "type" => "check",
            ],
            "product" => [
                "url" => "/product",
                "class" => "ProductController",
                "function" => "show",
                "type" => "normal",
                "template" => "product.php"
            ],
            "add-product" => [
                "url" => "/product/add",
                "class" => "ProductController",
                "function" => "showAdd",
                "type" => "normal",
                "template" => "addProduct.php"
            ],
            "check-add-product" => [
                "url" => "/check-add-product",
                "class" => "ProductController",
                "function" => "add",
                "type" => "check",
            ],
            "product-image" => [
                "url" => "/product/image/{id}",
                "class" => "ProductImageController",
                "function" => "show",
                "type" => "normal",
                "template" => "productImage.php"
            ],
            "check-delete-product-image" => [
                "url" => "/check-delete-product-image/{id}",
                "class" => "ProductImageController",
                "function" => "delete",
                "type" => "check",
            ],
            "check-add-product-image" => [
                "url" => "/check-add-product-image/{id}",
                "class" => "ProductImageController",
                "function" => "add",
                "type" => "check",
            ],
            "check-set-thumbnail-product-image" => [
                "url" => "/check-set-thumbnail-product-image/{id}",
                "class" => "ProductImageController",
                "function" => "setThumbnail",
                "type" => "check",
            ],
            "check-set-active-product" => [
                "url" => "/check-set-active-product/{id}",
                "class" => "ProductController",
                "function" => "setIsActiveTrue",
                "type" => "check",
            ],
            "check-set-deactivate-product" => [
                "url" => "/check-set-deactivate-product/{id}",
                "class" => "ProductController",
                "function" => "setIsActiveFalse",
                "type" => "check",
            ],
            "check-delete-product" => [
                "url" => "/check-delete-product/{id}",
                "class" => "ProductController",
                "function" => "delete",
                "type" => "check",
            ],
            "update-product" => [
                "url" => "/product/update/{id}",
                "class" => "ProductController",
                "function" => "showUpdate",
                "type" => "normal",
                "template" => "updateProduct.php"
            ],
            "check-update-product" => [
                "url" => "/check-update-product",
                "class" => "ProductController",
                "function" => "update",
                "type" => "check",
            ],


        ];

        $match = false;

        $request_uri = $this->parse_url();

        if ((!isset($_SESSION['admin_id']) && ((strcmp($request_uri, '/login') == 0) || strcmp($request_uri, '/check-login') == 0))
            || isset($_SESSION['admin_id'])) {
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
                        $controllerClassName = 'admin\controller\\' . $controllerClassName;
                        call_user_func_array(array(new $controllerClassName, $functionName), array($templateFilePath, $parameters));
                        $match = true;
                        break;

                    } else if ($routeType == "check") {
                        $controllerClassName = 'admin\controller\\' . $controllerClassName;
                        call_user_func_array(array(new $controllerClassName, $functionName), $parameters);
                        $match = true;
                        break;
                    }

                }
            }
            if ($match == false) {
                $pageNotFoundPath = __DIR__ . "/view/404.php";
                require_once($pageNotFoundPath);
            }
        } else {
            header('location: /admin/login');
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

