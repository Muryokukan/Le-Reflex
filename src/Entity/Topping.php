<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Topping
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?float $additionalPrice = null;

    /**
     * @var Collection<int, Pizza>
     */
    #[ORM\ManyToMany(targetEntity: Pizza::class, inversedBy: 'toppings')]
    private Collection $pizzas;

    public function __construct()
    {
        $this->pizzas = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->name ?? '';
    }

    // Getters and setters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getAdditionalPrice(): ?float
    {
        return $this->additionalPrice;
    }
    
    public function setAdditionalPrice(float $additionalPrice): self
    {
        $this->additionalPrice = $additionalPrice;
        return $this;
    }

    /**
     * @return Collection<int, Pizza>
     */
    public function getPizzas(): Collection
    {
        return $this->pizzas;
    }

    public function addPizza(Pizza $pizza): static
    {
        if (!$this->pizzas->contains($pizza)) {
            $this->pizzas->add($pizza);
        }

        return $this;
    }

    public function removePizza(Pizza $pizza): static
    {
        $this->pizzas->removeElement($pizza);

        return $this;
    }

    public function getPizzaList(): string
    {
        return implode(
            '<br>',
            $this->pizzas->map(
                fn($pizza) => $pizza->getName()
            )->toArray()
        );
    }
}
