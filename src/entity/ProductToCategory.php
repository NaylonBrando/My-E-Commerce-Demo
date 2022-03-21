<?php

namespace src\entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="src\repository\ProductToCategoryRepository")
 * @ORM\Table(name="product_to_category")
 */
class ProductToCategory
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private int $id;

    /**
     * @ORM\Column(type="integer", name="category_id")
     */
    private int $categoryId;

    /**
     * @ORM\Column(type="integer", name="product_id")
     */
    private int $productId;

    public function getId(): int
    {
        return $this->id;
    }

    public function getCategoryId(): string
    {
        return $this->categoryId;
    }

    public function setCategoryId(int $categoryId): void
    {
        $this->categoryId = $categoryId;
    }

    public function getProductId(): string
    {
        return $this->productId;
    }

    public function setProductId(int $productId): void
    {
        $this->productId = $productId;
    }




}