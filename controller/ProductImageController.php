<?php

namespace controller;

use src\entity\ProductImage;

class ProductImageController extends AbstractController
{
    /**
     * @return ProductImage[]|null
     */
    public function findImagesByProductId($productId): ?array
    {
        $em = $this->getEntityManager();
        $product = $em->getRepository(ProductImage::class)->findBy(['productId' => $productId]);
        if(!$product) {
            return null;
        }
        return $product;
    } 

}