<?php

namespace src\dto;

use src\entity\Review;

class ReviewWithUserDto
{
    private Review $review;

    private string $userName;

    private string $userLastName;

    private string $userEmail;

    private int $productId;

    private string $productTitle;

    private string $productSlug;


    public function getReview(): Review
    {
        return $this->review;
    }

    public function setReview(Review $review): void
    {
        $this->review = $review;
    }

    public function getUserName(): string
    {
        return $this->userName;
    }

    public function setUserName(string $userName): void
    {
        $this->userName = $userName;
    }

    public function getUserLastName(): string
    {
        return $this->userLastName;
    }

    public function setUserLastName(string $userLastName): void
    {
        $this->userLastName = $userLastName;
    }

    public function getUserEmail(): string
    {
        return $this->userEmail;
    }

    public function setUserEmail(string $userEmail): void
    {
        $this->userEmail = $userEmail;
    }

    public function getProductId(): int
    {
        return $this->productId;
    }

    public function setProductId(int $productId): void
    {
        $this->productId = $productId;
    }

    public function getProductTitle(): string
    {
        return $this->productTitle;
    }


    public function setProductTitle(string $productTitle): void
    {
        $this->productTitle = $productTitle;
    }


    public function getProductSlug(): string
    {
        return $this->productSlug;
    }


    public function setProductSlug(string $productSlug): void
    {
        $this->productSlug = $productSlug;
    }

}