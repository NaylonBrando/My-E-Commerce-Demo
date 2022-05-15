<?php

namespace src\controller;

class HomepageController extends AbstractController
{
    public function show($pageModulePath)
    {
        $pageModule = $pageModulePath;
        $templateFilePath = str_replace('homepage', 'homepageTemplate', $pageModulePath);
        $title = 'My-Ecommerce-Demo';
        
        $productController = new ProductController();
        $lastAddedProducts = $productController->getLastAddedProductCardGeneratorWithLimit(4);
        
        require_once($templateFilePath);

    }
}