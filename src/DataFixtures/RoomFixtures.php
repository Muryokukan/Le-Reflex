<?php

namespace App\DataFixtures;

use App\Entity\Room;
use App\Entity\RoomOption;
use App\Entity\RoomReservationSlot;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class RoomFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $slots = $this->generateSlots();
        foreach ($slots as $slot) {
            $manager->persist($slot);
        }

        $options = $this->generateOptions();
        foreach ($options as $option) {
            $manager->persist($option);
        }

        $rooms = $this->generateRooms($options);
        foreach ($rooms as $room) {
            $manager->persist($room);
        }

        $manager->flush();
    }

    private function generateSlots(): array
    {
        $slotsData = [
            ['name' => 'Après-midi', 'description' => '12h à 19h'],
            ['name' => 'Soir', 'description' => '19h à 2h'],
        ];

        $slots = [];
        foreach ($slotsData as $slotData) {
            $slot = new RoomReservationSlot();
            $slot->setName($slotData['name']);
            $slot->setDescription($slotData['description']);
            $slots[] = $slot;
        }

        return $slots;
    }

    private function generateOptions(): array
    {
        $optionsData = [
            [
                'name' => 'Terrasse',
                'description' => 'Grande terrasse arrière de x m², tables incluses',
                'price' => 300
            ],
            [
                'name' => 'Lumières et amplificateurs',
                'description' => null,
                'price' => 200
            ],
            [
                'name' => 'Serveur au comptoir',
                'description' => 'Entretient des verres, service toute la soirée',
                'price' => 300
            ],
            [
                'name' => 'Cuisine',
                'description' => 'Avec matériel pour une utilisation avec un professionnel',
                'price' => 500
            ],
        ];

        $options = [];
        foreach ($optionsData as $optionData) {
            $option = new RoomOption();
            $option->setName($optionData['name']);
            $option->setDescription($optionData['description']);
            $option->setPrice($optionData['price']);
            $options[] = $option;
        }

        return $options;
    }

    private function generateRooms($options): array
    {
        $optionsByName = [];
        foreach ($options as $option) {
            $optionsByName[$option->getName()] = $option;
        }

        $roomsData = [
            [
                'name' => 'Bar',
                'description' => null,
                'price' => 500,
                'options' => ['Terrasse', 'Lumières et amplificateurs', 'Serveur au comptoir'],
            ],
            [
                'name' => 'Restaurant',
                'description' => null,
                'price' => 600,
                'options' => ['Serveur au comptoir', 'Cuisine'],
            ],
        ];

        $rooms = [];
        foreach ($roomsData as $roomData) {
            $room = new Room();
            $room->setName($roomData['name']);
            $room->setDescription($roomData['description']);
            $room->setPrice($roomData['price']);
            foreach ($roomData['options'] as $optionName) {
                $room->addRoomOption($optionsByName[$optionName]);
            }
            $rooms[] = $room;
        }

        return $rooms;
    }
}
