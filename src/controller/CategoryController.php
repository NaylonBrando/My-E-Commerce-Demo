<?php

namespace src\controller;

use src\entity\Category;

class CategoryController extends AbstractController
{
    public function getByName($categoryName): Category|null
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
        //get subcategories with subcategories of subcategories
        $em = $this->getEntityManager();
        $categories = $em->getRepository(Category::class)->findBy(['parent_id' => $parentId]);
        $subCategories = [];
        foreach ($categories as $category) {
            $subCategories = array_merge($subCategories, $this->getSubCategories($category->getId()));
        }
        
        return array_merge($categories, $subCategories);
        
    }

    public function getById($id): null|Category
    {
        $em = $this->getEntityManager();
        return $em->getRepository(Category::class)->find($id);
    }
}