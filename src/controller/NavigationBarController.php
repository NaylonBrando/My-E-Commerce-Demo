<?php

namespace src\controller;

use src\entity\Category;

class NavigationBarController extends AbstractController
{
    public function selectCategoryRowGenerator($parentId= 0)
    {
        $str = '';
        $categoryController = new CategoryController();
        $mainCategoryArray = $categoryController->getCategoriesByParentId($parentId);
        foreach ($mainCategoryArray as $mainCategory) {
            $subCategories = $categoryController->getCategoriesByParentId($mainCategory->getId());
            $str .= $this->selectCategoryRow($mainCategory->getName(), $subCategories);
        }
        echo $str;

    }

    public function selectCategoryRow(string $parentCategoryName, $subCategories): string
    {
        $lowercaseParentCategoryName = strtolower($parentCategoryName);
        $str =
            "<div class=\"col-lg-3 col-6\">
                <div class=\"col-megamenu\">
                   <h6 class=\"title\"><a href=\"/category/$lowercaseParentCategoryName\">$parentCategoryName</a></h6>
                       <ul class=\"list-unstyled\">
                       ";
        /** @var Category[] $subCategories */
        foreach ($subCategories as $subCategory) {
            $subName = $subCategory->getName();
            $lowercaseSubName = strtolower($subName);
            $str .= "<li><a href=\"/category/$lowercaseSubName\">$subName</a></li>";
        }
        $str .= '</ul>
                </div>
            </div>';

        return $str;
    }
}