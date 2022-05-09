<?php

namespace src\entity;

use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="src\repository\ReviewRepository")
 * @ORM\Table(name="reviews")
 */
class Review
{
    public function __construct()
    {
        $this->status = false;
        $this->createdAt = new DateTimeImmutable();
    }

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private int $id;
    /**
     * @ORM\Column(type="integer")
     */
    private int $userId;
    /**
     * @ORM\Column(type="integer")
     */
    private int $productId;
    /**
     * @ORM\Column(type="text")
     */
    private string $title;
    /**
     * @ORM\Column(type="text")
     */
    private string $review;
    /**
     * @ORM\Column(type="integer")
     */
    private int $rating;
    /**
     * @ORM\Column(type="datetime", name="created_at")
     * @var DateTimeInterface
     */
    private DateTimeInterface $createdAt;
    /**
     * @ORM\Column(nullable=true, type="datetime", name="updated_at")
     * @var DateTimeInterface
     */
    private DateTimeInterface $updatedAt;
    /**
     * @ORM\Column(type="boolean", name="status")
     */
    private bool $status;

    public function getId(): int
    {
        return $this->id;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    public function getProductId(): int
    {
        return $this->productId;
    }

    public function setProductId(int $productId): void
    {
        $this->productId = $productId;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getReview(): string
    {
        return $this->review;
    }

    public function setReview(string $review): void
    {
        $this->review = $review;
    }

    public function getRating(): int
    {
        return $this->rating;
    }

    public function setRating(int $rating): void
    {
        $this->rating = $rating;
    }

    public function getCreatedAt(): DateTimeImmutable|DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable|DateTimeInterface $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt(): DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTimeInterface $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function isStatus(): bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): void
    {
        $this->status = $status;
    }
}