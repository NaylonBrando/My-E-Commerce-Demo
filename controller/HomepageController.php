<?php

namespace controller;

class HomepageController extends AbstractController
{

    public function show($pageModulePath)
    {
        $pageModule = $pageModulePath;
        $templateFilePath = str_replace('homepage', 'homepageTemplate', $pageModulePath);
        $title = 'My-Ecommerce-Demo';
        require_once($templateFilePath);

    }


}