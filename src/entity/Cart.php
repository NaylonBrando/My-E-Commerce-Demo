<?php

namespace src\entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="src\repository\CartRepository")
 * @ORM\Table(name="cart")
 */
class Cart
{
    public function __construct()
    {
        $this->grandTotal = 0;
    }
    
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private int $id;
    
    /**
     * @ORM\OneToMany(targetEntity="CartItem", mappedBy="cart")
     */
    private $cartItem;
    
    /**
     * @ORM\OneToOne(targetEntity="src\entity\User", inversedBy="cart")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;
    
    /**
     * @ORM\Column(type="float")
     */
    private float $grandTotal;
    
 
    public function getId(): int
    {
        return $this->id;
    }
    
    public function getCartItem(): PersistentCollection
    {
        return $this->cartItem;
    }
    
    public function setCartItem(CartItem $cartItem): void
    {
        $this->cartItem = $cartItem;
    }
    
    public function setUser(User $user): void
    {
        $this->user = $user;
    }
    
    public function getGrandTotal(): float
    {
        return $this->grandTotal;
    }
    
    public function setGrandTotal(float $grandTotal): void
    {
        $this->grandTotal = $grandTotal;
    }
}