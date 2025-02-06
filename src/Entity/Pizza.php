<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity]
#[Vich\Uploadable]
class Pizza
{
   #[ORM\Id]
   #[ORM\GeneratedValue]
   #[ORM\Column]
   private ?int $id = null;

   #[ORM\Column(length: 255)]
   private ?string $name = null;

   #[ORM\Column(type: Types::TEXT, nullable: true)]
   private ?string $description = null;

   #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
   private ?float $price = null;

   #[ORM\Column(length: 255, nullable: true)]
   private ?string $imageFilename = null;

   #[Vich\UploadableField(mapping: 'pizzas', fileNameProperty: 'imageFilename')]
   private ?File $imageFile = null;

   #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
   private ?\DateTimeInterface $updatedAt = null;

   #[ORM\ManyToMany(targetEntity: Topping::class, mappedBy: 'pizzas')]
   private Collection $toppings;

   public function __construct()
   {
       $this->toppings = new ArrayCollection();
   }

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

   public function getDescription(): ?string
   {
       return $this->description;
   }

   public function setDescription(?string $description): self
   {
       $this->description = strip_tags($description);
       return $this;
   }

   public function getPrice(): ?float
   {
       return $this->price;
   }

   public function setPrice(float $price): self
   {
       $this->price = $price;
       return $this;
   }

   public function getImageFilename(): ?string
   {
       return $this->imageFilename;
   }

   public function setImageFilename(?string $imageFilename): self
   {
       $this->imageFilename = $imageFilename;
       return $this;
   }

   public function setImageFile(?File $imageFile = null): void
   {
       $this->imageFile = $imageFile;
       if (null !== $imageFile) {
           $this->updatedAt = new \DateTime();
       }
   }

   public function getImageFile(): ?File
   {
       return $this->imageFile;
   }

   public function getUpdatedAt(): ?\DateTimeInterface
   {
       return $this->updatedAt;
   }

   public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
   {
       $this->updatedAt = $updatedAt;
       return $this;
   }

   public function getToppings(): Collection
   {
       return $this->toppings;
   }

   public function addTopping(Topping $topping): static
   {
       if (!$this->toppings->contains($topping)) {
           $this->toppings->add($topping);
           $topping->addPizza($this);
       }

       return $this;
   }

   public function removeTopping(Topping $topping): static
   {
       if ($this->toppings->removeElement($topping)) {
           $topping->removePizza($this);
       }

       return $this;
   }

   public function getToppingList(): string
   {
       return implode(
           '<br>',
           $this->toppings->map(
               fn($topping) => $topping->getName()
           )->toArray()
       );
   }
}