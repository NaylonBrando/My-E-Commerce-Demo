<?php

namespace controller;

use src\entity\Category;

class CategoryController extends AbstractController
{
    public function getCategoryByName($categoryName): Category|null
    {
        $em = $this->getEntityManager();
        return $em->getRepository(Category::class)->findOneBy(['name' => $categoryName]);
    }


    /**
     * @return Category[]
     */
    public function getCategoriesByParentId($parentId): array
    {
        $em = $this->getEntityManager();
        return $em->getRepository(Category::class)->findBy(['parent_id' => $parentId]);
    }

    /**
     * @return Category[] | null
     */
    public function getSubCategories($parentId): null|array
    {
        global $categoryArray;
        $em = $this->getEntityManager();
        $category = $em->getRepository(Category::class)->findBy(['parent_id' => $parentId]);
        foreach ($category as $cat) {
            $categoryArray[] = $cat;
            $this->getSubCategories($cat->getId());
        }
        return $categoryArray;
    }

}