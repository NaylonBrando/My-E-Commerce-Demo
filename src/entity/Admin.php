<?php

namespace src\entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="src\repository\AdminRepository")
 * @ORM\Table(name="admins")
 */
class Admin
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private int $id;

    /**
     * @ORM\Column(type="string", name="first_name")
     */
    private string $firstName;

    /**
     * @ORM\Column(type="string", name="last_name")
     */
    private string $lastName;

    /**
     * @ORM\Column(type="string")
     */
    private string $email;

    /**
     * @ORM\Column(type="string")
     */
    private string $password;

    /**
     * @ORM\Column(type="string")
     */
    private string $adminType;

    public function __construct()
    {
        $this->adminType = "admin";
    }

    public function getId(): int
    {
        return $this->id;
    }


    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName($firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName($lastName): void
    {
        $this->lastName = $lastName;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail($email): void
    {
        $this->email = $email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword($password): void
    {
        $this->password = $password;
    }

    public function getAdminType(): string
    {
        return $this->adminType;
    }

    public function setAdminType(string $adminType): void
    {
        $this->adminType = $adminType;
    }

}