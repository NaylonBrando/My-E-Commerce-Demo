<?php

namespace src\dto;

use src\entity\Product;
use src\entity\ProductImage;

class ProductWithImageDto
{
    public function __construct()
    {
        $this->images = [];
    }

    private Product $product;

    /**
     * @var ProductImage[]
     */
    private array $images;

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function setProduct(Product $product): void
    {
        $this->product = $product;
    }

    /**
     * @return ProductImage[]
     */
    public function getImages(): array
    {
        return $this->images;
    }

    /**
     * @param ProductImage[] $images
     */
    public function setImages(array $images): void
    {
        $this->images = $images;
    }
}