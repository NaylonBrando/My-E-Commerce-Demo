<?php

namespace admin\src\controller;

class HomepageController
{
    public function index(string $templateFile)
    {
        require_once($templateFile);
    }
}