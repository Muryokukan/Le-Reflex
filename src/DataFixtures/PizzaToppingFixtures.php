<?php

namespace App\DataFixtures;

use App\Entity\Pizza;
use App\Entity\Topping;
use DateTimeImmutable;
use Faker\Factory;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class PizzaToppingFixtures extends Fixture
{
    private $faker;

    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        // Create Toppings with a price set
        $toppings = [
            ['name' => 'Extra Fromage', 'price' => 1.50],
            ['name' => 'Pepperoni', 'price' => 2.00],
            ['name' => 'Champignons', 'price' => 1.20],
            ['name' => 'Oignons', 'price' => 1.00],
            ['name' => 'Poivrons', 'price' => 1.30],
            ['name' => 'Saucisse Italienne', 'price' => 2.50],
            ['name' => 'Olives Noires', 'price' => 1.40],
            ['name' => 'Basilic Frais', 'price' => 1.00],
        ];

        // Store topping references
        $toppingReferences = [];
        foreach ($toppings as $toppingData) {
            $topping = new Topping();
            $topping
                ->setName($toppingData['name'])
                ->setAdditionalPrice($toppingData['price']); // Fixed price for each topping

            $manager->persist($topping);
            // Store reference for future use
            $toppingReferences[$toppingData['name']] = $topping;
        }

        // Pizza names, descriptions, and prices
        $pizzaTypes = [
            [
                'name' => 'Pepperoni Festa',
                'description' => 'Pizza au pepperoni épicé, garnie de mozzarella fondue et de sauce tomate signature',
                'price' => 12.00
            ],
            [
                'name' => 'Quattro Formaggi Deluxe',
                'description' => 'Mélange luxueux de quatre fromages premium : mozzarella, gorgonzola, parmesan et fontina',
                'price' => 13.50
            ],
            [
                'name' => 'Vegetariana Supreme',
                'description' => 'Légumes frais du jardin, y compris champignons, poivrons, oignons et olives noires',
                'price' => 11.00
            ],
            [
                'name' => 'BBQ Chicken Specialty',
                'description' => 'Poulet BBQ tendre avec oignons rouges, coriandre et notre sauce BBQ spéciale',
                'price' => 12.50
            ],
            [
                'name' => 'Ultimate Supreme',
                'description' => 'Tout y est ! Garnie de pepperoni, saucisse, légumes frais et fromage supplémentaire',
                'price' => 14.00
            ]
        ];

        // Create Pizzas
        foreach ($pizzaTypes as $pizzaData) {
            $createdAt = $this->faker->dateTimeBetween('-6 months', 'now');
            $createdAt = DateTimeImmutable::createFromMutable($createdAt);

            $pizza = new Pizza();
            $pizza
                ->setName($pizzaData['name'])
                ->setDescription($pizzaData['description'])
                ->setPrice($pizzaData['price']);

            // If the pizza is "BBQ Chicken Specialty" (my favourite), add all toppings
            if ($pizzaData['name'] === 'BBQ Chicken Specialty') {
                foreach ($toppingReferences as $topping) {
                    $pizza->addTopping($topping);
                }
            }

            $manager->persist($pizza);
        }

        $manager->flush();
    }
}
