<?php

namespace src\entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="routes")
 */
class Routes
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @ORM\Column(type="string")
     */
    private $url;

    /**
     * @ORM\Column(type="string")
     */
    private $class;

    /**
     * @ORM\Column(type="string")
     */
    private $function;

    /**
     * @ORM\Column(type="string")
     */
    private $type;


    /**
     * @ORM\Column(type="string")
     */
    private $template;

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName($name): void
    {
        $this->name = $name;
    }


    public function getClass(): string
    {
        return $this->class;
    }

    public function setClass($class): void
    {
        $this->class = $class;
    }

    public function getFunction(): string
    {
        return $this->function;
    }

    public function setFunction($function): void
    {
        $this->function = $function;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType($type): void
    {
        $this->type = $type;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl($url): void
    {
        $this->url = $url;
    }

    public function getTemplate(): string
    {
        return $this->template;
    }

    public function setTemplate($template): void
    {
        $this->template = $template;
    }

}