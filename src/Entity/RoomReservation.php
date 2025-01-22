<?php

namespace App\Entity;

use App\Enum\RoomReservationStatus;
use App\Repository\RoomReservationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RoomReservationRepository::class)]
#[ORM\HasLifecycleCallbacks]
class RoomReservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $lastname = null;

    #[ORM\Column(length: 255)]
    private ?string $firstname = null;

    #[ORM\Column(length: 255)]
    private ?string $phoneNumber = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $email = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $reservationDate = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $totalAmount = null;

    #[ORM\Column(enumType: RoomReservationStatus::class)]
    private ?RoomReservationStatus $status = null;

    /**
     * @var Collection<int, Room>
     */
    #[ORM\ManyToMany(targetEntity: Room::class, inversedBy: 'roomReservations')]
    private Collection $rooms;

    /**
     * @var Collection<int, RoomOption>
     */
    #[ORM\ManyToMany(targetEntity: RoomOption::class, inversedBy: 'roomReservations')]
    private Collection $roomOptions;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    private ?RoomReservationSlot $slot = null;

    public function __construct()
    {
        $this->rooms = new ArrayCollection();
        $this->roomOptions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;
        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;
        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): static
    {
        $this->phoneNumber = $phoneNumber;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    #[ORM\PrePersist]
    public function setCreatedAtValue(): void
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getReservationDate(): ?\DateTimeImmutable
    {
        return $this->reservationDate;
    }

    public function setReservationDate(\DateTimeImmutable $reservationDate): static
    {
        $this->reservationDate = \DateTimeImmutable::createFromFormat('Y-m-d', $reservationDate->format('Y-m-d'));
        return $this;
    }

    public function getTotalAmount(): ?string
    {
        return $this->totalAmount;
    }

    public function setTotalAmount(?string $totalAmount): static
    {
        $this->totalAmount = $totalAmount;
        return $this;
    }

    public function calculateTotalAmount(): string
    {
        $total = 0;

        foreach ($this->rooms as $room) {
            $total += (float) ($room->getPrice() ?? 0);
        }

        foreach ($this->roomOptions as $option) {
            $total += (float) ($option->getPrice() ?? 0);
        }

        if ($this->hasAllRooms()) {
            $total -= 100;
        }

        return (string) $total;
    }

    private function hasAllRooms(): bool
    {
        $totalRoomsAvailable = 2;
        return $this->rooms->count() === $totalRoomsAvailable;
    }

    public function updateTotalAmount(): void
    {
        $this->totalAmount = (string) $this->calculateTotalAmount();
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function updateTotalAmountBeforePersist(): void
    {
        $this->updateTotalAmount();
    }

    public function getStatus(): ?RoomReservationStatus
    {
        return $this->status;
    }

    public function setStatus(RoomReservationStatus $status): static
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return Collection<int, Room>
     */
    public function getRooms(): Collection
    {
        return $this->rooms;
    }

    public function addRoom(Room $room): static
    {
        if (!$this->rooms->contains($room)) {
            $this->rooms->add($room);
        }
        return $this;
    }

    public function removeRoom(Room $room): static
    {
        $this->rooms->removeElement($room);
        return $this;
    }

    public function getRoomsList(): string
    {
        return implode(
            '<br>',
            $this->rooms->map(
                fn($room) => $room->getName()
            )->toArray()
        );
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
        }
        return $this;
    }

    public function removeRoomOption(RoomOption $roomOption): static
    {
        $this->roomOptions->removeElement($roomOption);
        return $this;
    }

    public function getOptionsList(): string
    {
        return implode(
            '<br>',
            $this->roomOptions->map(
                fn($option) => '-' . $option->getName()
            )->toArray()
        );
    }

    public function getSlot(): ?RoomReservationSlot
    {
        return $this->slot;
    }

    public function setSlot(?RoomReservationSlot $slot): static
    {
        $this->slot = $slot;

        return $this;
    }
}