<?php

namespace App\Entity;

use App\Repository\RoomRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RoomRepository::class)]
class Room
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 0)]
    private ?string $price = null;

    /**
     * @var Collection<int, RoomOption>
     */
    #[ORM\ManyToMany(targetEntity: RoomOption::class, mappedBy: 'rooms')]
    private Collection $roomOptions;

    public function __construct()
    {
        $this->roomOptions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

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

    /**
     * @return Collection<int, RoomOption>
     */
    public function getRoomOptions(): Collection
    {
        return $this->roomOptions;
    }

    public function addRoomOption(RoomOption $roomOption): static
    {
        if (!$this->roomOptions->contains($roomOption)) {
            $this->roomOptions->add($roomOption);
            $roomOption->addRoom($this);
        }

        return $this;
    }

    public function removeRoomOption(RoomOption $roomOption): static
    {
        if ($this->roomOptions->removeElement($roomOption)) {
            $roomOption->removeRoom($this);
        }

        return $this;
    }
}
