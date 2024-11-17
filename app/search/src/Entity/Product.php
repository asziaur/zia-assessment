<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: "App\Repository\ProductRepository")]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $id;

    #[ORM\Column(type: "string", length: 255)]
    private string $name;

    #[ORM\Column(type: "float")]
    private float $price;

    #[ORM\Column(type: "datetime")]
    private \DateTime $createdAt;

    #[ORM\Column(type: "string", length: 255)]
    private string $category;

    // Getter and Setter for 'id'
    public function getId(): int
    {
        return $this->id;
    }

    // Getter and Setter for 'name'
    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    // Getter and Setter for 'price'
    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    // Getter and Setter for 'createdAt'
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    // Getter and Setter for 'category'
    public function getCategory(): string
    {
        return $this->category;
    }

    public function setCategory(string $category): self
    {
        $this->category = $category;

        return $this;
    }
}
