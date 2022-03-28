<?php

namespace src\entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="src\repository\ProductImageRepository")
 * @ORM\Table(name="product_images")
 */
class ProductImage
{

    public function __construct()
    {
        $this->isThumbnail = false;
    }

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private int $id;

    /**
     * @ORM\Column(type="integer", name="product_id")
     */
    private int $productId;

    /**
     * @ORM\Column(type="string")
     */
    private string $path;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $isThumbnail;


    public function getId(): int
    {
        return $this->id;
    }

    public function getProductId(): int
    {
        return $this->productId;
    }

    public function setProductId(int $productId): void
    {
        $this->productId = $productId;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function setPath(string $path): void
    {
        $this->path = $path;
    }

    public function getIsThumbnail(): bool
    {
        return $this->isThumbnail;
    }

    public function setIsThumbnail(bool $isThumbnail): void
    {
        $this->isThumbnail = $isThumbnail;
    }


}