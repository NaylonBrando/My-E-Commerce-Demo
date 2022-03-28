<?php

namespace controller;

class HomepageController extends AbstractController
{

    function index($templateFile)
    {
        include_once $templateFile;
    }
}