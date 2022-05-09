<?php

namespace admin\controller;

use src\entity\ProductToCategory;

class ProductToCategoryController extends AdminAbstractController
{
    public function add(int $productId, int $categoryId): bool
    {
        $productToCategory = new ProductToCategory();
        $productToCategory->setProductId($productId);
        $productToCategory->setCategoryId($categoryId);

        $em = $this->getEntityManager();

        $em->persist($productToCategory);
        $em->flush();

        if ($productToCategory->getId()) {
            return true;
        } else {
            return false;
        }
    }

    public function delete(int $productId): bool
    {
        $em = $this->getEntityManager();
        $productToCategory = $em->getRepository(ProductToCategory::class)->findOneBy(['productId' => $productId]);
        if ($productToCategory) {
            $em->remove($productToCategory);
            $em->flush();
            return true;
        } else {
            return false;
        }
    }

    public function updateProductCategory(int $productId, int $categoryId): bool
    {
        $em = $this->getEntityManager();
        /** @var ProductToCategory $productToCategory */
        $productToCategory = $em->getRepository(ProductToCategory::class)->findOneBy(['productId' => $productId]);
        if ($productToCategory) {
            $productToCategory->setCategoryId($categoryId);
            $em->persist($productToCategory);
            $em->flush();
            return true;
        } else {
            return false;
        }
    }
}