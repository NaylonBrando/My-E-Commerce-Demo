<?php

namespace admin\controller;

class HomepageController
{

    public function index(string $templateFile)
    {
        require_once($templateFile);


    }

}