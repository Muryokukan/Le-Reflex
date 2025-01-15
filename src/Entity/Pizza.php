<?php

namespace App\Entity;

use App\Repository\PizzaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PizzaRepository::class)]
class Pizza
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Pizza = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $price = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $thumbnail = null;

    /**
     * @var Collection<int, Topping>
     */
    #[ORM\ManyToMany(targetEntity: Topping::class, inversedBy: 'pizzas')]
    private Collection $toppings;

    public function __construct()
    {
        $this->toppings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPizza(): ?string
    {
        return $this->Pizza;
    }

    public function setPizza(string $Pizza): static
    {
        $this->Pizza = $Pizza;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getThumbnail(): ?string
    {
        return $this->thumbnail;
    }

    public function setThumbnail(?string $thumbnail): static
    {
        $this->thumbnail = $thumbnail;

        return $this;
    }

    /**
     * @return Collection<int, Topping>
     */
    public function getToppings(): Collection
    {
        return $this->toppings;
    }

    public function addTopping(Topping $topping): static
    {
        if (!$this->toppings->contains($topping)) {
            $this->toppings->add($topping);
        }

        return $this;
    }

    public function removeTopping(Topping $topping): static
    {
        $this->toppings->removeElement($topping);

        return $this;
    }
}
